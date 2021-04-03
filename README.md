# MantisBT Libravatar Plugin

This plugin is released under the [GNU General Public License v2](https://opensource.org/licenses/GPL-2.0)

## Description

The **Libravatar** plugin is based on the Gravatar plugin that comes default with a MantisBT installation. This plugin uses libravatar rather than gravatar. As such, some variables and code have been removed that is not needed. Some comments have been changed as well.

Avatars are retrieved from [Libravatar](https://www.libravatar.org/). They are retrieved based on a user's email address.

Original plugin can be found [here](https://github.com/mantisbt/mantisbt/tree/master/plugins/Gravatar)

## TODO

The future plan is to extend this plugin to allow an administrator choose the libravatar API supported site, instead of relying on just libravatar. This may require having to bring back "ratings"

## Installation

1. While logged into your Mantis installation as an administrator, go to 'Manage' -> "Manage Plugins"

2. In the "Available Plugins" list, find the "Libravatar" plugin, and then click "Install"

3. In your `config_inc.php`, ensure `$g_show_avatar` is set to `ON`

4. Check to see if avatars are appearing. In majority of cases, they should appear automatically.

## Supported Versions

- MantisBT 1.3.x - supported
