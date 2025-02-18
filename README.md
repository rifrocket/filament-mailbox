# Filament Mailbox Plugin

A synchronous, advanced mailbox plugin for Laravel Filament 3. This package offers a robust webmail client experience similar to Gmail/Hotmail and advanced webmail tools like Roundcube. It supports real-time synchronization with Livewire polling or Laravel Reverb WebSockets for IMAP IDLE, full IMAP integration using the Webklex Laravel IMAP package, and a complete set of mailbox functionalities such as folder management, email composition, reply/forward, attachments, and search.

---

## Features

- **IMAP Integration:**  
  Seamless connection to your IMAP server to fetch emails, mark them as seen, and manage folders.

- **Real-Time Synchronization:**  
  Choose between Livewire polling (default) or Laravel Reverb WebSockets for near real-time updates.

- **Full Mailbox Functionality:**  
  Provides an inbox listing, detailed email view, folder management, email composition, reply/forward capabilities, attachments, and search functionality.

- **Admin Panel Integration:**  
  Built as a Filament plugin for Laravel, providing a modern, responsive UI using Tailwind CSS.

---

## Installation

### Requirements
- **PHP:** ^8.1
- **Laravel:** 11
- **Filament:** 3.x
- **Webklex Laravel IMAP:** ^2.6
- *(Optional)* **Laravel Reverb:** for WebSockets (if using IMAP IDLE)

### Steps

1. **Install the Package via Composer:**
    ```bash
    composer require rifrocket/filament-mailbox
    ```

2. **Publish the Package Assets:**
    Publish configuration and migrations:
    ```bash
    php artisan vendor:publish --tag=filament-mailbox-config
    php artisan vendor:publish --tag=filament-mailbox-migrations
    ```

3. **Run Migrations:**
    ```bash
    php artisan migrate
    ```

4. **Configure Your Environment:**
    Update your `.env` file with your IMAP credentials and settings. For example:
    ```dotenv
    MAILBOX_SYNC_METHOD=polling
    MAILBOX_POLLING_INTERVAL=30
    MAILBOX_REVERB_ENABLED=false
    REVERB_APP_KEY=your_reverb_key
    REVERB_APP_SECRET=your_reverb_secret
    REVERB_HOST=127.0.0.1
    REVERB_PORT=6001
    ```

5. **Enable IMAP on Your Email Server:**
    Ensure IMAP is enabled (e.g., for Gmail, enable IMAP in settings and use an App Password).

---

## Settings

The configuration file (`config/mailbox.php`) provides key settings:

- **Synchronization Method:**  
  Set `sync_method` to either `polling` or `reverb` to choose between Livewire polling or Laravel Reverb WebSockets.

- **Polling Configuration:**  
  Define the refresh interval using the `MAILBOX_POLLING_INTERVAL` environment variable.

- **Reverb Configuration:**  
  If using WebSockets for IMAP IDLE, enable `MAILBOX_REVERB_ENABLED` and set the required keys (`REVERB_APP_KEY`, `REVERB_APP_SECRET`, etc.).

Customize these settings in your `.env` file to suit your deployment environment.

---

## Usage

### Filament Admin Panel

- **Mailbox Dashboard:**  
  Access the Mailbox Dashboard in your Filament admin panel to view email statistics, folder details, and more.

- **Email Resources:**  
  Use the provided Filament resources to list emails, view email details, and compose new messages.

- **Real-Time Sync:**  
  Emails are fetched in near real time using either Livewire polling or Laravel Reverb WebSockets based on your configuration.

### IMAP Commands

- **Manual Email Fetching:**  
  Use the Artisan command to fetch emails:
  ```bash
  php artisan mailbox:fetch-emails --folder=INBOX
  ```
  You can schedule this command in Laravel's scheduler for regular synchronization.

### Customization

- **Views:**  
  Customize the Blade templates in `resources/views/filament` for a personalized UI.

- **Routes:**  
  Additional routes are defined in `routes/web.php` and can be extended as needed.

- **Extensibility:**  
  Extend the plugin by leveraging the service classes (e.g., `ImapService`) and Filament resources provided.

---

## Changelog

### 1.0.0
- Initial release of the Filament Mailbox Plugin.
- Basic IMAP integration with email fetching, marking as seen, and folder management.
- Filament resources for listing, viewing, and composing emails.
- Dashboard and widgets for real-time email statistics.
- Support for both Livewire polling and Laravel Reverb WebSockets.

---

## Security Vulnerabilities / Support

### Security

- **Secure Credentials:**  
  Always store your IMAP and sensitive credentials in the `.env` file.
  
- **Up-to-Date:**  
  Keep Laravel, Filament, and all dependencies updated to benefit from the latest security patches.

- **Reporting Vulnerabilities:**  
  If you discover any security vulnerabilities, please report them to the developer at [mohammad.arif9999@gmail.com](mailto:mohammad.arif9999@gmail.com).

### Support

- **Issue Tracker:**  
  For bug reports and feature requests, please use the repository's issue tracker.
  
- **Direct Contact:**  
  You can also contact the developer directly via email for further support.

---

## License Options

This package is open-sourced software licensed under the [MIT License](LICENSE). You are free to use, modify, and distribute the software under the terms of this license.

---

## Contributing

We welcome contributions! To contribute:

1. **Fork the Repository:**  
   Create a personal fork of the project.
   
2. **Create a Branch:**  
   Develop your feature or fix in a dedicated branch.
   
3. **Write Tests:**  
   Ensure your changes are covered by tests.
   
4. **Submit a Pull Request:**  
   Open a PR with a detailed description of your changes.
   
5. **Follow Guidelines:**  
   Adhere to the coding standards and best practices outlined in the documentation.

---


## Acknowledgments

- [Laravel](https://laravel.com)
- [Filament](https://filamentphp.com)
- [Webklex Laravel IMAP](https://github.com/Webklex/laravel-imap)
- [Laravel Reverb](https://github.com/laravel-reverb) *(if used)*

---

## Contact

For questions, feedback, or support, please contact:  
**Mohammad Arif (RifRocket)**  
Email: [mohammad.arif9999@gmail.com](mailto:mohammad.arif9999@gmail.com)


