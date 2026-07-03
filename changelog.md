## Version History

### 0.1.0 - 2026-07-03

#### Breaking

- Add Laravel 11, 12 and 13 compatibility.
- Drop Laravel 9 / Livewire 2 support in favor of Livewire 3 / 4.
- Move package classes from `App\...` to vendor namespaces.
- Load package routes, views and Livewire components automatically.

Existing installations that published old `App\...` classes should remove those copies
or migrate references to the vendor package classes.

#### Fixed

- Add PHPUnit/Testbench tests, PHPStan config and GitHub Actions matrix.
- Fix Livewire 3+ model bindings.
- Fix token refresh, API error handling, subscriber modal data and multi-client sync keys.

### v. 0.0.1

- Initial alpha release
