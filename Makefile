.PHONY: build

BOOKS = $(shell find ./docs -type d -maxdepth 1 -mindepth 1 -exec basename {} \;)

# Build all books
build:
	$(foreach BOOK,$(BOOKS), \
        $(MAKE) build-$(BOOK) \
	)

# Build certain book
build-%:
	echo $*
	cd page; hugo \
		--cleanDestinationDir \
		--environment $* \
		--destination ../build/$* \
		--logLevel info \
		--baseURL https://docs.contao.org/$*/

# Live server for certain book
live-%:
	cd page; hugo server \
		--cleanDestinationDir \
		--environment $* \
		--destination ../build/$* \
		--logLevel info

clean:
	rm -r build
