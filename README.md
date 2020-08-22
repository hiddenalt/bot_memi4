# Memer the Bot

<p align="center"><img height="256" width="256" src="https://github.com/hiddenalt/bot_memi4/blob/master/resources/images/logo.png?raw=true"></p>

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![gitlocalized ](https://gitlocalize.com/repo/5206/whole_project/badge.svg)](https://gitlocalize.com/repo/5206/whole_project?utm_source=badge)

## About
**Memer the Bot** _(Bot Memich)_ is a cross-platform bot focused on generating random memes. Oriented to be hosted on VK and Telegram.

Built with Laravel, Botman, Lighthouse and Vue.js.

This repo is a redux version of a bot, originally hosted at [vk.com/bot_memi4](https://vk.com/bot_memi4).

## Implemented features

⚠️The redux version hasn't got all the features of the original version yet.

<table>
    <tbody>
        <tr>
            <th align="center">Name</th>
            <th align="center">Status (beta/stable)</th>
            <th align="center">Comments</th>
        </tr>
        <tr>
            <td>Generating memes 'When...'</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Feature of randomly generating memes according to data-sets.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Generating demotivators</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Feature of randomly generating demotivators according to data-sets.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Generating 4-block comics</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Feature of randomly generating simple 4-block comics with 4 images & labels according to data-sets.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Creating user-made memes 'When...'</td>
            <td>✅ Stable</td>
            <td>
                <ul>
                    <li>Feature of creating memes with custom image & text.</li>
                    <li>✅ = custom image support</li>
                    <li>✅ = UTF-8 support</li>
                    <li>❌ = emoji support</li>
                    <li>❌ = ellipsis if text is sliced</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Creating user-made demotivators</td>
            <td>✅ Stable</td>
            <td>
                <ul>
                    <li>Feature of creating memes with custom demotivator image & text.</li>
                    <li>✅ = custom image support</li>
                    <li>✅ = UTF-8 support</li>
                    <li>❌ = emoji support</li>
                    <li>❌ = ellipsis if text is sliced</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Creating user-made 4-block comics</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Feature of creating memes with custom 4 images & labels.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>A database of all the public generated memes with features of search, tagging, commenting, cross-platform liking, etc.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Mood changes</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Changing the bot's mood according to the user's messages: make happy/sad face (avatar), etc.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Public MemeDB</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>A database of all the public generated memes with features of search, tagging, commenting, cross-platform liking, etc.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Public GraphQL API</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>GraphQL API mount.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Localization</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Bot multi-language support.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>User (public) dashboard</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>User dashboard page with extra features.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Admin dashboard</td>
            <td>❌ Not implemented yet</td>
            <td>
                <ul>
                    <li>Admin dashboard page for monitoring the bot.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td align="center">...</td>
            <td align="center">...</td>
            <td align="center">...</td>
        </tr>
    </tbody>
</table>

See ["Ideas to be implemented" board](https://github.com/hiddenalt/bot_memi4/projects/1) for viewing the upcoming features.

## Requirements

- PHP 7.4.x+
- MySQL 5.6+
- Multi-thread web-server (Apache/Nginx)

## Configure the bot

TODO: configure the bot section

## Documentation (Wiki)
TODO: documentation (dedicated wiki)


## Localization

[![gitlocalized ](https://gitlocalize.com/repo/5206/ru/badge.svg)](https://gitlocalize.com/repo/5206/ru?utm_source=badge)

- __Source language__: English
- __Target languages__: Russian 

If you have better translation of source/target languages, your pull requests are welcome.

See [localization project page](https://gitlocalize.com/repo/5206) for the detailed localization list.

## Security Vulnerabilities
If you discover a security vulnerability, please send an e-mail to The Hiddenalt Project at [admin@hiddenalt.ru](mailto:admin@hiddenalt.ru). All security vulnerabilities will be fixed as soon as possible.

## License
Memer the Bot is free software distributed under the terms of the MIT license.