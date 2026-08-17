# Changelog

## Unreleased

### Added

- `TwigProdModule`: compiles templates in the build phase and serves them read-only from `{appDir}/var/build/twig`.
- `FilesystemLoader` receives `rootPath`, so cache keys no longer depend on the working directory.

### Changed

- PHP 8.2 or later is required, following `bear/sunday`.

### Migration

Install `TwigProdModule` in the production context module, the one chained over the application module:

```php
// src/Module/ProdModule.php
$this->install(new TwigProdModule());
```

An application that pins `TwigOptions` itself keeps managing its own cache, so the override has to go:

```diff
-$options = ['debug' => false, 'cache' => $this->appMeta->appDir . '/var/tmp/twig'];
-$this->bind()->annotatedWith(TwigOptions::class)->toInstance($options);
+$this->install(new TwigProdModule());
```

Serving no longer compiles: a missing artifact raises `Exception\TemplateNotCompiled`. `bear/package` has to
run the `twig` compile step during `bin/bear.compile`.
