# phpgdo-dog-tick

A corona game written for the
[phpgdo](https://github.com/gizmore/phpgdo)
[Dog chatbot](https://github.com/gizmore/phpgdo-dog).

## phgdo-dog-tick Credits

- TuB (for inspiration)
- tehron for support
- dloser for free time
- livinskull (beer)
- quangtenemy (b'n'bj)


## phgdo-dog-tick Rules
- Infected players can infect other uninfected players.
- This works across networks and all channels / visible users and different connectors like IRC, Discord and Telegram

### phgdo-dog-tick Installation
This is not free software!

### phgdo-dog-tick Dog commands
- [cc.init](./Method/Init.php) (to get the first infected player)
- [cc.tick](./Method/Init.php) (to infect others)
- [cc.ticked](./Method/T) (show tick status for a player)
- [cc.stats](./Method/Stats.php) (show game statistics)
- [cc.ustats](./Method/UserStats.php) (show player statistics)
- [cc.reset](./Method/Reset.php) (reset the game or a cheater)

### gdo6 dependencies
- gdo6
- gdo6-dog

### @TODO

[14:40:36] <@gizmore> hmm, idee: Corona variants
[14:40:51] <@gizmore> + idee Corona random points per tick
