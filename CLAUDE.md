# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin called "UTM Persistence" that maintains marketing tracking parameters (UTM codes, click IDs, etc.) across page loads and appends them to specified domain links throughout a user's session.

## Architecture

### Core Components

1. **Main Plugin File** (`utm-persistence.php`): Entry point and main class
   - `UTM_Persistence` class using singleton pattern
   - Frontend script enqueuing
   - Settings registration

2. **Admin Class** (`includes/class-utm-persistence-admin.php`): Admin interface
   - Settings page under Settings menu
   - Domain and parameter management UI
   - AJAX handlers for adding/removing items

3. **Frontend Script** (`assets/js/utm-persistence.js`): Client-side tracking
   - Captures UTM parameters from URL on page load
   - Stores parameters in sessionStorage
   - Decorates matching domain links with stored parameters
   - Uses MutationObserver for dynamic content

4. **Admin Assets**:
   - `assets/js/admin.js`: Admin UI interactions
   - `assets/css/admin.css`: Admin styling

### Key Features

- Configurable list of domains to decorate with parameters
- Customizable tracking parameters (not just UTM)
- Session-based storage (clears when browser closes)
- Handles dynamically loaded content via MutationObserver
- Lightweight frontend footprint

## Development Commands

Since this is a WordPress plugin without build tools:

```bash
# No build/compile steps required - this is a traditional WordPress plugin

# To test the plugin:
# 1. Copy the plugin folder to wp-content/plugins/
# 2. Activate via WordPress admin dashboard
# 3. Configure domains in Settings > UTM Persistence

# Check PHP syntax (if PHP CLI is available):
find . -name "*.php" -exec php -l {} \;
```

## WordPress Coding Standards

Follow WordPress PHP coding standards when making modifications:
- Use tabs for indentation
- Use WordPress Settings API for options
- Escape output with appropriate functions
- Use `wp_localize_script()` for passing data to JavaScript

## Default Tracking Parameters

The plugin tracks these parameters by default:
- `utm_source`, `utm_medium`, `utm_campaign`
- `utm_adgroup`, `utm_content`, `utm_term`
- `gclid` (Google Click ID)
- `fbclid` (Facebook Click ID)
- `refer` (custom referral parameter)

## Options Storage

Settings stored in `utm_persistence_settings`:
- `domains` - Array of domain strings to decorate
- `params` - Array of parameter names to track

## JavaScript Architecture

The frontend script (`utm-persistence.js`):
1. On page load, captures any tracking params from current URL
2. Merges with existing sessionStorage values
3. Finds all links matching configured domains
4. Appends stored parameters to those links
5. Sets up MutationObserver to handle new links

## Security Considerations

- Settings require `manage_options` capability
- All output is escaped appropriately
- sessionStorage is domain-scoped by browser
- No sensitive data is transmitted to external servers

## Customization Points

To extend this plugin:
- Add new default parameters in `get_default_settings()`
- Modify link decoration logic in `utm-persistence.js`
- Add cookie-based persistence as alternative to sessionStorage
- Add support for first-touch vs last-touch attribution
