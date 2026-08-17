# Changelog

## Unreleased

### Added

- `TwigProdModule`: compiles templates in the build phase and serves them read-only from `{buildDir}/twig`.
- `FilesystemLoader` receives `rootPath`, so cache keys no longer depend on the working directory.

### Changed

- PHP 8.2 or later is required, following `bear/sunday`.
- The Twig loader is now `RootRelativeLoader`, a `FilesystemLoader` subclass that reports template paths
  relative to the root path. Compiled artifacts no longer embed the absolute path of the machine that
  compiled them, so a Twig error in production names the deployed template. A template root bound outside
  `appDir` keeps its absolute path, in the cache key as much as in the reported path, and stays unportable.

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
