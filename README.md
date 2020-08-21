# Community Gardens GeoDataHub Integration by Guelph Digital Service
This repository holds the code for a custom plugin which allows integration of a Wordpress instance to the Community Gardens & Pollinators API of the Guelph GeoDataHub.

- First download the latest release of this theme and drop the zip into the `New Plugins` section of WordPress
- Next create a page to act as the parent/archive page of our new Garden post types. This page is NOT created for you within this plugin by default. However you now have full access to the new `community-garden` post type and respective taxonomies (can be viewed by turning on switch in plugin settings) to display how you please.
- Next head to the plugin settings page and enter the path of the page created in the previous step
- Enter the URL of the API to be pulled from
- Click 'Pull from GeoDataHub' button or turn on 'Automatically fetch from GeoDataHub'

## Behind-the-curtain
On activation, this plugin first set's up it's options to be saved within the site database then creates the respective custom post type called  `community-gardens` to store our new Gardens. All settings for this plugin are saved within a single option array called `gds_geodatahub_gardens_genset`. By default the new Garden custom post type is invisible to admins, to show it in the UI, turn on the switch in the plugin settings page. The plugin sets the parent page of new `community-garden` posts by default to be the root of the site, however this can also be configured within the plugin settings. Next, we create a new custom taxonomy within the Garden post type and call `populateGardenTypes.php` to fill our new taxonomy with preset categories and parents.

On deactivation, the plugin will unregister the custom post type and flush rewrite rules.

### Development Tips
  This plugin has been hardcoded based on the database configuration of the GeoDataHub's gardens on September of 2020. In the future, if these database schema's change some modifications may be required. As well as comments within the code to stear you in the right direction, here are some tips.
  - To add more plugin settings, add more fields to the `gds_geodatahub_gardens_settings_page` function within `admin/gds-geodatahub-gardens-admin.php`
  - To add, remove or modify the default Garden categories visit the bottom of `partials/populateGardenTypes.php`
  - To add, remove or modify the default Garden fields visit ...
  - To configure how Garden posts are displayed or add more custom fields visit `partials/single-garden-geodatahub.php`


## Versioning
Version numbers will be given to major releases, where minor fixes and development will be tracked through branch and commit history in this repo. For this project, we aim to use [SemVer](http://semver.org/) for versioning. For the major release versions, see the [tags on this repository](https://github.com/CityOfGuelph-Webservices/TwentyTwelve-CityOfGuelph/tags).

## Authors

* **[Durish, Nic](https://github.com/Durishn)**

## License
Along with WordPress, this project is licensed under the GNU V3 General License.
