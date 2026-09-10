<?php

declare(strict_types=1);

namespace Terminal42\ExtensionsDocs;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class BookApplication extends Application
{
    public function __construct(private readonly string $projectDirectory)
    {
        parent::__construct('terminal42 extensions documentation');

        $this->add($this->createBuildCommand());
        $this->add($this->createLiveCommand());
        $this->add($this->createDeployCommand());
        $this->add($this->createDeployPreviewCommand());
    }

    private function createBuildCommand(): Command
    {
        $command = (new Command('build'))
            ->setDescription('Build one or all documentation books')
            ->addArgument('book', InputArgument::OPTIONAL, 'Book to build')
        ;

        return $command->setCode(function (InputInterface $input, OutputInterface $output): int {
            $book = $input->getArgument('book');

            if (is_string($book)) {
                return $this->buildBook($this->validateBook($book), $output);
            }

            foreach ($this->books() as $availableBook) {
                $result = $this->buildBook($availableBook, $output);

                if (Command::SUCCESS !== $result) {
                    return $result;
                }
            }

            return Command::SUCCESS;
        });
    }

    private function createLiveCommand(): Command
    {
        $command = (new Command('live'))
            ->setDescription('Run the live server for a documentation book')
            ->addArgument('book', InputArgument::REQUIRED, 'Book to serve')
        ;

        return $command->setCode(function (InputInterface $input, OutputInterface $output): int {
            return $this->liveBook($this->validateBook((string) $input->getArgument('book')), $output);
        });
    }

    private function createDeployPreviewCommand(): Command
    {
        $command = (new Command('deploy-preview'))
            ->setDescription('Build and deploy a protected documentation preview')
            ->addArgument('book', InputArgument::REQUIRED, 'Book to deploy')
            ->addOption('user', null, InputOption::VALUE_REQUIRED, 'HTTP Basic Auth user')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'HTTP Basic Auth password')
        ;

        return $command->setCode(function (InputInterface $input, OutputInterface $output): int {
            $book = $this->validateBook((string) $input->getArgument('book'));
            $credentials = $this->previewCredentials($input);
            $deployment = $this->deploymentConfiguration();
            $result = $this->buildBook($book, $output);

            if (Command::SUCCESS !== $result) {
                return $result;
            }

            $protection = $this->protectBuild($book, $credentials, $deployment);

            try {
                return $this->replaceRemoteBook($book, $deployment, $output);
            } finally {
                $this->restoreBuild($protection);
            }
        });
    }

    private function createDeployCommand(): Command
    {
        $command = (new Command('deploy'))
            ->setDescription('Build and deploy one or all documentation books')
            ->addArgument('book', InputArgument::OPTIONAL, 'Book to deploy')
        ;

        return $command->setCode(function (InputInterface $input, OutputInterface $output): int {
            $book = $input->getArgument('book');
            $books = is_string($book) ? [$this->validateBook($book)] : $this->books();
            $deployment = $this->deploymentConfiguration();

            foreach ($books as $availableBook) {
                $result = $this->buildBook($availableBook, $output);

                if (Command::SUCCESS !== $result) {
                    return $result;
                }
            }

            foreach ($books as $availableBook) {
                $result = $this->replaceRemoteBook($availableBook, $deployment, $output);

                if (Command::SUCCESS !== $result) {
                    return $result;
                }
            }

            return Command::SUCCESS;
        });
    }

    /**
     * @return list<string>
     */
    private function books(): array
    {
        $directories = glob($this->projectDirectory . '/docs/*', GLOB_ONLYDIR) ?: [];
        $books = array_map('basename', $directories);
        sort($books);

        return $books;
    }

    private function validateBook(string $book): string
    {
        if (1 !== preg_match('/^[A-Za-z0-9._-]+$/D', $book) || !in_array($book, $this->books(), true)) {
            throw new InvalidArgumentException(sprintf('Unknown documentation book "%s".', $book));
        }

        return $book;
    }

    private function buildBook(string $book, OutputInterface $output): int
    {
        return $this->runProcess([
            'hugo',
            '--cleanDestinationDir',
            '--environment',
            $book,
            '--destination',
            '../build/' . $book,
            '--logLevel',
            'info',
            '--baseURL',
            'https://extensions.terminal42.ch/docs/' . $book . '/',
        ], $this->projectDirectory . '/page', $output);
    }

    private function liveBook(string $book, OutputInterface $output): int
    {
        return $this->runProcess([
            'hugo',
            'server',
            '--cleanDestinationDir',
            '--environment',
            $book,
            '--destination',
            '../build/' . $book,
            '--logLevel',
            'info',
        ], $this->projectDirectory . '/page', $output);
    }

    /**
     * @param list<string> $command
     */
    private function runProcess(array $command, string $workingDirectory, OutputInterface $output): int
    {
        $process = new Process($command, $workingDirectory);
        $process->setTimeout(null);

        return $process->run(static function (string $type, string $buffer) use ($output): void {
            $output->write($buffer, false, OutputInterface::OUTPUT_RAW);
        });
    }

    /**
     * @return array{user: string, password: string}
     */
    private function previewCredentials(InputInterface $input): array
    {
        $credentials = [
            'user' => (string) $input->getOption('user'),
            'password' => (string) $input->getOption('password'),
        ];

        if (1 !== preg_match('/^[A-Za-z0-9._-]+$/D', $credentials['user'])) {
            throw new InvalidArgumentException('The preview user contains unsupported characters.');
        }

        if ('' === $credentials['password']) {
            throw new InvalidArgumentException('The preview password must not be empty.');
        }

        return $credentials;
    }

    /**
     * @return array{host: string, user: string, port: int, target: string, identityFile: string|null}
     */
    private function deploymentConfiguration(): array
    {
        $deployment = [
            'host' => $this->environment('DEPLOY_HOST'),
            'user' => $this->environment('DEPLOY_USERNAME'),
            'port' => (int) $this->environment('DEPLOY_PORT'),
            'target' => rtrim($this->environment('DEPLOY_TARGET_PATH'), '/'),
            'identityFile' => $this->optionalEnvironment('DEPLOY_IDENTITY_FILE'),
        ];

        if (1 !== preg_match('/^[A-Za-z0-9.-]+$/D', $deployment['host'])) {
            throw new InvalidArgumentException('DEPLOY_HOST contains unsupported characters.');
        }

        if (1 !== preg_match('/^[A-Za-z0-9._-]+$/D', $deployment['user'])) {
            throw new InvalidArgumentException('DEPLOY_USERNAME contains unsupported characters.');
        }

        if (1 > $deployment['port'] || 65535 < $deployment['port']) {
            throw new InvalidArgumentException('DEPLOY_PORT must be between 1 and 65535.');
        }

        if (1 !== preg_match('~^/(?:[A-Za-z0-9._-]+/)*[A-Za-z0-9._-]+$~D', $deployment['target'])) {
            throw new InvalidArgumentException('DEPLOY_TARGET_PATH must be an absolute directory containing only safe path characters.');
        }

        if (null !== $deployment['identityFile'] && !is_readable($deployment['identityFile'])) {
            throw new InvalidArgumentException('DEPLOY_IDENTITY_FILE does not point to a file.');
        }

        return $deployment;
    }

    private function environment(string $name): string
    {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

        if (!is_string($value) || '' === $value) {
            throw new RuntimeException(sprintf('Missing %s in .env.local.', $name));
        }

        return $value;
    }

    private function optionalEnvironment(string $name): ?string
    {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

        return is_string($value) && '' !== $value ? $value : null;
    }

    /**
     * @param array{user: string, password: string} $credentials
     * @param array{host: string, user: string, port: int, target: string, identityFile: string|null} $deployment
     *
     * @return array{passwordFile: string, htaccessFile: string, originalHtaccess: string|null}
     */
    private function protectBuild(string $book, array $credentials, array $deployment): array
    {
        $buildDirectory = $this->projectDirectory . '/build/' . $book;
        $htaccessFile = $buildDirectory . '/.htaccess';
        $passwordFile = $buildDirectory . '/.preview.htpasswd';
        $originalHtaccess = is_file($htaccessFile) ? file_get_contents($htaccessFile) : null;
        $hash = password_hash($credentials['password'], PASSWORD_BCRYPT);

        if (false === $originalHtaccess || false === $hash) {
            throw new RuntimeException('Could not prepare the preview protection.');
        }

        if (false === file_put_contents($passwordFile, $credentials['user'] . ':' . $hash . "\n")) {
            throw new RuntimeException('Could not write the preview password file.');
        }

        $remotePasswordFile = $deployment['target'] . '/' . $book . '/.preview.htpasswd';

        if (false === file_put_contents($htaccessFile, $this->createHtaccess($remotePasswordFile) . "\n")) {
            @unlink($passwordFile);
            throw new RuntimeException('Could not write the preview .htaccess file.');
        }

        return compact('passwordFile', 'htaccessFile', 'originalHtaccess');
    }

    private function createHtaccess(string $passwordFile): string
    {
        $passwordFile = str_replace(['\\', '"'], ['\\\\', '\\"'], $passwordFile);

        return <<<HTACCESS
RewriteEngine off

AuthType Basic
AuthName "Protected documentation preview"
AuthUserFile "{$passwordFile}"
Require valid-user

<Files ".preview.htpasswd">
    Require all denied
</Files>

<IfModule mod_headers.c>
    Header always set X-Robots-Tag "noindex, nofollow, noarchive"
</IfModule>
HTACCESS;
    }

    /**
     * @param array{passwordFile: string, htaccessFile: string, originalHtaccess: string|null} $protection
     */
    private function restoreBuild(array $protection): void
    {
        @unlink($protection['passwordFile']);

        if (null === $protection['originalHtaccess']) {
            @unlink($protection['htaccessFile']);
        } else {
            file_put_contents($protection['htaccessFile'], $protection['originalHtaccess']);
        }
    }

    /**
     * @param array{host: string, user: string, port: int, target: string, identityFile: string|null} $deployment
     */
    private function replaceRemoteBook(string $book, array $deployment, OutputInterface $output): int
    {
        $result = $this->removeRemoteBook($book, $deployment, $output);

        if (Command::SUCCESS !== $result) {
            return $result;
        }

        return $this->runProcess(array_merge([
            'scp',
        ], $this->sshOptions($deployment, true), [
            '-r',
            $this->projectDirectory . '/build/' . $book,
            $deployment['user'] . '@' . $deployment['host'] . ':' . $deployment['target'],
        ]), $this->projectDirectory, $output);
    }

    /**
     * @param array{host: string, user: string, port: int, target: string, identityFile: string|null} $deployment
     */
    private function removeRemoteBook(string $book, array $deployment, OutputInterface $output): int
    {
        return $this->runProcess(array_merge([
            'ssh',
        ], $this->sshOptions($deployment, false), [
            $deployment['user'] . '@' . $deployment['host'],
            'rm',
            '-rf',
            '--',
            $deployment['target'] . '/' . $book,
        ]), $this->projectDirectory, $output);
    }

    /**
     * @param array{host: string, user: string, port: int, target: string, identityFile: string|null} $deployment
     *
     * @return list<string>
     */
    private function sshOptions(array $deployment, bool $scp): array
    {
        $options = [
            '-o',
            'BatchMode=yes',
            '-o',
            'StrictHostKeyChecking=accept-new',
            $scp ? '-P' : '-p',
            (string) $deployment['port'],
        ];

        if (null !== $deployment['identityFile']) {
            $options[] = '-i';
            $options[] = $deployment['identityFile'];
        }

        return $options;
    }
}
