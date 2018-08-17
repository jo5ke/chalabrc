---
title: Breddefantasy API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://boopro.tech'>Powered by boopro.tech</a>
---
<!-- START_INFO -->
# Info

Welcome to the breddefantasy API reference.
<!-- END_INFO -->

#Admin

Admin control panel routes, admin authentication required for all routes
<!-- START_bbd0b22ec07db584b0bc9f07388ee19c -->
## Get clubs

Getter for all clubs in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/myTeam/getClubs" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/getClubs",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/myTeam/getClubs`

`HEAD api/myTeam/getClubs`


<!-- END_bbd0b22ec07db584b0bc9f07388ee19c -->

<!-- START_08bee7b718e5a5f2904692752fae36f9 -->
## Get club

Getter for a specific club// params: id (club id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/myTeam/getClub" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/getClub",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/myTeam/getClub`


<!-- END_08bee7b718e5a5f2904692752fae36f9 -->

<!-- START_5bec254cc8a7577b23f01ff428b00d27 -->
## Get clubs

Getter for all clubs in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getClubs" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getClubs",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getClubs`

`HEAD api/admin/getClubs`


<!-- END_5bec254cc8a7577b23f01ff428b00d27 -->

<!-- START_234f790c867bb87b5d82a4f9203e1bba -->
## Get club

Getter for a specific club// params: id (club id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getClub" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getClub",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getClub`

`HEAD api/admin/getClub`


<!-- END_234f790c867bb87b5d82a4f9203e1bba -->

<!-- START_f1a8754308d261ac3e6a1d20207daa31 -->
## Create club

Create a new club// params: l_id (league id),name, image (base64 image),

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postClub" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postClub",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postClub`


<!-- END_f1a8754308d261ac3e6a1d20207daa31 -->

<!-- START_c426598a68f628fb76be83e26b4eba53 -->
## Remove a club

Remove an existing club // params: l_id (league id),name,

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeClub" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeClub",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeClub`


<!-- END_c426598a68f628fb76be83e26b4eba53 -->

<!-- START_abf7a0216383a7801b614d4a146218bd -->
## Update club

Update an existing club// params: l_id (league id),id (club id), name

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updateClub" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updateClub",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updateClub`


<!-- END_abf7a0216383a7801b614d4a146218bd -->

<!-- START_5b65f845642f89e4a654e21a8a3a2a74 -->
## Get players

Getter for all players in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getPlayers" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getPlayers",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getPlayers`

`HEAD api/admin/getPlayers`


<!-- END_5b65f845642f89e4a654e21a8a3a2a74 -->

<!-- START_645229fec6767e12756e8622159496f4 -->
## Get players by club

Getter for all players by clubs in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getPlayersByClub" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getPlayersByClub",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getPlayersByClub`

`HEAD api/admin/getPlayersByClub`


<!-- END_645229fec6767e12756e8622159496f4 -->

<!-- START_4f555d28d36bbe365e93a46d47a353eb -->
## Get player

Getter for a specific player// params: id (player id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getPlayer" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getPlayer",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getPlayer`

`HEAD api/admin/getPlayer`


<!-- END_4f555d28d36bbe365e93a46d47a353eb -->

<!-- START_d04027be320badc6244fe1ffee89cfc2 -->
## Create player

Create a new player// params: l_id (league id),first_name, last_name, position(in:GK,DEF,MID,ATK), number, price(integer in NOK), club_name, wont_play (integer,% of not playing next round), reason(string, reason of not playing)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postPlayer" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postPlayer",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postPlayer`


<!-- END_d04027be320badc6244fe1ffee89cfc2 -->

<!-- START_53473d3139b4c29fecf4e45e582ec724 -->
## Remove a player

Remove existing player // params: id (player id)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removePlayer" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removePlayer",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removePlayer`


<!-- END_53473d3139b4c29fecf4e45e582ec724 -->

<!-- START_99d65b05e9b3f1008b34808aba63c058 -->
## Update player

Update an existing club// params: l_id (league id),first_name, last_name, position(in:GK,DEF,MID,ATK), number, price(integer in NOK), club_name, wont_play (integer,% of not playing next round), reason(string, reason of not playing)

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updatePlayer" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updatePlayer",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updatePlayer`


<!-- END_99d65b05e9b3f1008b34808aba63c058 -->

<!-- START_b5ab9d8fb01c5e85e9d3788628eac85c -->
## Get leagues

Getter for all leagues

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getLeagues" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getLeagues",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getLeagues`

`HEAD api/admin/getLeagues`


<!-- END_b5ab9d8fb01c5e85e9d3788628eac85c -->

<!-- START_fa87f0fe0268036ecd44760c29346405 -->
## Get league

Getter for a specific league// params: id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getLeague",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getLeague`

`HEAD api/admin/getLeague`


<!-- END_fa87f0fe0268036ecd44760c29346405 -->

<!-- START_b653bc496648c88ad4e7cf1fde8c1b49 -->
## Create league

Create a new league// params: l_id (league id), name, number_of_rounds (integer, number of rounds to be created in league),

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postLeague",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postLeague`


<!-- END_b653bc496648c88ad4e7cf1fde8c1b49 -->

<!-- START_23e2264bddd70ec5b4a42545cb898cfa -->
## Remove leagues

Remove an existing league // params: id (league id)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeLeague",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeLeague`


<!-- END_23e2264bddd70ec5b4a42545cb898cfa -->

<!-- START_8da2aa7fd185cbe5b7097fb39d46e83c -->
## Update league

Update an existing league // params: id (league id), number_of_rounds

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updateLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updateLeague",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updateLeague`


<!-- END_8da2aa7fd185cbe5b7097fb39d46e83c -->

<!-- START_e5c9dd05f136d7279c63e09a5e2c4b96 -->
## Get matches(deprecated)

Getter for all matches in a single round in selected league // params: l_id (league id), r_id (round_id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getMatches" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getMatches",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getMatches`

`HEAD api/admin/getMatches`


<!-- END_e5c9dd05f136d7279c63e09a5e2c4b96 -->

<!-- START_d9f3b266172e2d1e98204f4f8c87719e -->
## Get matches by rounds

Getter for all matches by rounds in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getMatchesByRounds" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getMatchesByRounds",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getMatchesByRounds`

`HEAD api/admin/getMatchesByRounds`


<!-- END_d9f3b266172e2d1e98204f4f8c87719e -->

<!-- START_18462a1314c7ece2973f35cbb753176c -->
## Get match

Getter for a specific match // params: id (match id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getMatch" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getMatch",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getMatch`

`HEAD api/admin/getMatch`


<!-- END_18462a1314c7ece2973f35cbb753176c -->

<!-- START_0c8f9969481435c0ecf633cd1404ea69 -->
## Create match

Create a new match, creates empty stats for players of both clubs for that match // params: l_id (league id),c1_name (club 1 name), c2_name (club 2 name), time(timestamp of match), r_no(number of round) OPTIONAL:  c1_score (club 1 score, default null),  c2_score (club 2 score, default null), odd_1, odd_x, odd_2 (double values, betting odds), link (link of the match on coolbet)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postMatch" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postMatch",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postMatch`


<!-- END_0c8f9969481435c0ecf633cd1404ea69 -->

<!-- START_366a085fa09c5f99485e102783fe28ca -->
## Remove match

Remove an existing match, removes players stats from both clubs for that match // params: l_id (league id), id (match id)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeMatch" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeMatch",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeMatch`


<!-- END_366a085fa09c5f99485e102783fe28ca -->

<!-- START_76efb8f1e6091774aad893015d863450 -->
## Update match

Update an existing match // params: l_id (league id), time(timestamp of match), r_no(number of round) OPTIONAL:  c1_score (club 1 score, default null),  c2_score (club 2 score, default null), odd_1, odd_x, odd_2 (double values, betting odds), link (link of the match on coolbet)

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updateMatch" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updateMatch",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updateMatch`


<!-- END_76efb8f1e6091774aad893015d863450 -->

<!-- START_efdac62d0f41b2e110e697cb32f19c17 -->
## Get rounds

Getter for all rounds in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getRounds" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getRounds",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getRounds`

`HEAD api/admin/getRounds`


<!-- END_efdac62d0f41b2e110e697cb32f19c17 -->

<!-- START_d76964f90a929594355d19b5e23dc0e6 -->
## Get round

Getter for a specific round // params: id (round id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getRound" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getRound",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getRound`

`HEAD api/admin/getRound`


<!-- END_d76964f90a929594355d19b5e23dc0e6 -->

<!-- START_eea323a44e0f06c414b37c6dd32eaff2 -->
## Update round deadline

Update an existing round deadline // params: l_id (league id), r_no (round number), time (timestamp of deadline)

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/setDeadline" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/setDeadline",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/setDeadline`


<!-- END_eea323a44e0f06c414b37c6dd32eaff2 -->

<!-- START_7bffd878cbfd37a60a22d18051928ce8 -->
## Get users

Getter for all users in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getUsers" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getUsers",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getUsers`

`HEAD api/admin/getUsers`


<!-- END_7bffd878cbfd37a60a22d18051928ce8 -->

<!-- START_623877db2ae2c1602afbebd45e3cf069 -->
## Get user

Getter for a specific user // params: id (user uuid)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getUser" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getUser",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getUser`

`HEAD api/admin/getUser`


<!-- END_623877db2ae2c1602afbebd45e3cf069 -->

<!-- START_f9f7b21772fc794146ea7d8053822241 -->
## Create user

Create a new user, with squad and league which is selected in navbar // params: l_id (league id), name, email, password

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postUser" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postUser",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postUser`


<!-- END_f9f7b21772fc794146ea7d8053822241 -->

<!-- START_08c3d90f015af2e7124b06ac6c9761f8 -->
## Remove user

Remove an existing user and his squad // params: id (user id)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeUser" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeUser",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeUser`


<!-- END_08c3d90f015af2e7124b06ac6c9761f8 -->

<!-- START_0788532168025bae896ce0df689b0e48 -->
## Update user

Update an existing user // params: l_id (league id), name, email, transfers(integer, -1 for unlimited transfers), points (integer)

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updateUser" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updateUser",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updateUser`


<!-- END_0788532168025bae896ce0df689b0e48 -->

<!-- START_887468c1505b50112e5f0029c7be8a21 -->
## Get squads

Getter for all users  in selected league with team value, money in bank and option to reset their squads // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getSquads" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getSquads",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getSquads`

`HEAD api/admin/getSquads`


<!-- END_887468c1505b50112e5f0029c7be8a21 -->

<!-- START_3c06567705dbd090270c339b9506327d -->
## Reset squad

Reset specific users squad in selected league // params: l_id (league id)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/resetSquad" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/resetSquad",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/resetSquad`


<!-- END_3c06567705dbd090270c339b9506327d -->

<!-- START_1c33e39967bf7de36c77fa74c09ad1ff -->
## Get admins

Getter for all admins in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getAdmins" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getAdmins",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getAdmins`

`HEAD api/admin/getAdmins`


<!-- END_1c33e39967bf7de36c77fa74c09ad1ff -->

<!-- START_4ab866b8c97461a826fb9cb6745ce8df -->
## Get admin

Getter for a specific admin // params: id (user id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getAdmin" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getAdmin",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getAdmin`

`HEAD api/admin/getAdmin`


<!-- END_4ab866b8c97461a826fb9cb6745ce8df -->

<!-- START_800d00bc742555efbb1ec2cb3caa3c4e -->
## Create admin

Create a new user with admin role, squad and league which is selected in navbar // params: l_id (league id), name, email, password

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postAdmin" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postAdmin",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postAdmin`


<!-- END_800d00bc742555efbb1ec2cb3caa3c4e -->

<!-- START_91fcbc4093522943e071bff8e4045c13 -->
## Remove admin

Remove an existing user with admin role alongs with squad // params: uuid (user uuid)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeAdmin" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeAdmin",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeAdmin`


<!-- END_91fcbc4093522943e071bff8e4045c13 -->

<!-- START_dae1907b202c20dff55ff5eaced144fe -->
## Update club

Update an existing club// params: id (user uuid),name, email

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updateAdmin" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updateAdmin",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updateAdmin`


<!-- END_dae1907b202c20dff55ff5eaced144fe -->

<!-- START_5350839d1be336c65000e903dbee8265 -->
## Make admin

Promote an existing user to admin // params: email (user email)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/makeAdmin" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/makeAdmin",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/makeAdmin`


<!-- END_5350839d1be336c65000e903dbee8265 -->

<!-- START_e3f356120b02f32f8f2a6a79a8866b7e -->
## Unmake admin

Demote an existing admin to user // params: email (user email)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/unMakeAdmin" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/unMakeAdmin",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/unMakeAdmin`


<!-- END_e3f356120b02f32f8f2a6a79a8866b7e -->

<!-- START_3bf2917b1bed8cf428f801c510fe1662 -->
## Get articles

Getter for all news articles in selected league // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getArticles" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getArticles",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getArticles`

`HEAD api/admin/getArticles`


<!-- END_3bf2917b1bed8cf428f801c510fe1662 -->

<!-- START_a3e0907f1bd664c638c7136c3a07733e -->
## Get article

Getter for a specific news article // params: id (article id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getArticle" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getArticle",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getArticle`

`HEAD api/admin/getArticle`


<!-- END_a3e0907f1bd664c638c7136c3a07733e -->

<!-- START_0de025a640bffa735e0cf0dfaede04cb -->
## Create article

Create a new article // params: l_id (league id), title, image (base64 image) body, public (in:0,1), scheduled_time (timestamp for scheduling article publishing)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postArticle" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postArticle",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postArticle`


<!-- END_0de025a640bffa735e0cf0dfaede04cb -->

<!-- START_1328335368793052a34163acc83498e7 -->
## Remove article

Remove an existing article // params: l_id (league id),name, image (base64 image),

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeArticle" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeArticle",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeArticle`


<!-- END_1328335368793052a34163acc83498e7 -->

<!-- START_309c71211ffa4c83b2b127787c0a7198 -->
## Update article

Update an existing article // params: title, image (base64 image) body, public (in:0,1), scheduled_time (timestamp for scheduling article publishing)

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/admin/updateArticle" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/updateArticle",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/admin/updateArticle`


<!-- END_309c71211ffa4c83b2b127787c0a7198 -->

<!-- START_c6d2b2950f2ffc4934a9266a113e58cf -->
## Get clubs on match

Getter for clubs with players in selected match // params: m_id (match id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getClubsByMatch" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getClubsByMatch",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getClubsByMatch`

`HEAD api/admin/getClubsByMatch`


<!-- END_c6d2b2950f2ffc4934a9266a113e58cf -->

<!-- START_109cf237707109faa70174c58bd83e9d -->
## Get players stats

Getter for all players with stats in selected league on Post Match Info // params: l_id (league id), m_id (match_id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getPlayersStats" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getPlayersStats",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getPlayersStats`

`HEAD api/admin/getPlayersStats`


<!-- END_109cf237707109faa70174c58bd83e9d -->

<!-- START_5e01d5990afc970f17abdc66e1b54411 -->
## Update player stats

Update single player stats on match on Post Match Info on Edit button in every row // params:   "data": { player_id, round_id, m_id ( match id), assist, captain, clean, kd_3strike, k_save, miss, own_goal, red, yellow, score, start, sub, total   }, l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/postPlayerStats" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/postPlayerStats",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/postPlayerStats`


<!-- END_5e01d5990afc970f17abdc66e1b54411 -->

<!-- START_bf48f98f11447d62b966ec4e761c24ea -->
## api/admin/playerTotalPoints

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/playerTotalPoints" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/playerTotalPoints",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/playerTotalPoints`


<!-- END_bf48f98f11447d62b966ec4e761c24ea -->

<!-- START_42303cdba728990ce091d22d27b36d9f -->
## Get tips

Getter for all tips in selected league, returning an array of tip and user object // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getTips" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getTips",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getTips`

`HEAD api/admin/getTips`


<!-- END_42303cdba728990ce091d22d27b36d9f -->

<!-- START_dcae613aa47cba38c4003cbc8abc498a -->
## Get tip

Getter for a specific tip // params: id (tip id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/admin/getTip" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/getTip",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/admin/getTip`

`HEAD api/admin/getTip`


<!-- END_dcae613aa47cba38c4003cbc8abc498a -->

<!-- START_6fd8c9bfd923ed1b14e428dff31a4087 -->
## Remove tip

Remove an existing tip // params: id (tip id)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/admin/removeTip" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/removeTip",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/admin/removeTip`


<!-- END_6fd8c9bfd923ed1b14e428dff31a4087 -->

<!-- START_e6fd5d2ff53d839303b09e60a12a4d54 -->
## Calculate users points

Evaluate users points based on previously entered match stats in database. Triggered on Post Match Info tab on 'Lock points' button // params: l_id (league id), m_id (match id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/evaluateUserPoints" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/evaluateUserPoints",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/evaluateUserPoints`


<!-- END_e6fd5d2ff53d839303b09e60a12a4d54 -->

<!-- START_0fbdb8c17937e22d0b8ff0a95c0e8082 -->
## Change round backward

Update current round of selected league on a previous round number (if not 1st round) // params: l_id (league id),

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/prevRound" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/prevRound",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/prevRound`


<!-- END_0fbdb8c17937e22d0b8ff0a95c0e8082 -->

<!-- START_82dfa22b3974f40412c1a3b7a55db1b4 -->
## Change round towards

Update current round of selected league on a next round number (if not last round). This functions also triggers calculating calculating user points based on all matches in the current round // params: l_id (league id),

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/nextRound" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/nextRound",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/nextRound`


<!-- END_82dfa22b3974f40412c1a3b7a55db1b4 -->

<!-- START_fa38715892b800f5daa18d25cd48fc8c -->
## Send newsletter

Store newsletter template and send it on emails of all users in selected league ( everyone=null ) or to everyone (everyone=true) // params: l_id (league id), everyone(in 0,1 ), test(0,1) optional: subject, title, text, title1, h1, text1, title2, h2, text2, title3, h3, text3, image1 (base64 image), image2 (base64 image)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/admin/newsletter" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/admin/newsletter",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/admin/newsletter`


<!-- END_fa38715892b800f5daa18d25cd48fc8c -->

#Home

Home,Points,Ranking,Stats,News pages routes
<!-- START_5a6599f3ecfca4d9787e34f0f3e9212d -->
## Get all news

Fetch all public published news (public=1,published=1)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/news" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/news",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/news`

`HEAD api/news`


<!-- END_5a6599f3ecfca4d9787e34f0f3e9212d -->

<!-- START_4e7aaa64e15ff280774aff89fe3ff93d -->
## Get latests news

Fetch the last published article for homepage (published=1,public=1)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getLatestNews" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getLatestNews",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getLatestNews`


<!-- END_4e7aaa64e15ff280774aff89fe3ff93d -->

<!-- START_df38b26c6a41f65e7b061f36d59372ba -->
## Get latests news per league

Fetch the last published article in certain league (published=1) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getLatestNewsByLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getLatestNewsByLeague",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getLatestNewsByLeague`

`HEAD api/getLatestNewsByLeague`


<!-- END_df38b26c6a41f65e7b061f36d59372ba -->

<!-- START_baa35b25abf848d76b60ec3e946044e1 -->
## Get top five users

Fetch top 5 users by points for each league

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/topFivePlayers" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/topFivePlayers",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/topFivePlayers`

`HEAD api/topFivePlayers`


<!-- END_baa35b25abf848d76b60ec3e946044e1 -->

<!-- START_48c3ea248c19784f738e8dac2ed59e09 -->
## Get single article

Getter for an article // params: slug (slug of the article in database)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getArticle/{slug}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getArticle/{slug}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getArticle/{slug}`

`HEAD api/getArticle/{slug}`


<!-- END_48c3ea248c19784f738e8dac2ed59e09 -->

<!-- START_12e37982cc5398c7100e59625ebb5514 -->
## Get users on Ranking page

Fetch all users in league, or search them by name  // params: l_id (league id) optional: per_page(users to be displayed per page), term (string to be searched by last or first name)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/users" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/users",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/users`


<!-- END_12e37982cc5398c7100e59625ebb5514 -->

<!-- START_11e6e58e01a29d2940ac3a7cb2f01a61 -->
## Get users points per round

Getter for all users with their points per round // params: l_id (league id) optional: per_page(users to be displayed per page), term (string to be searched by last or first name)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getUserPointsPerRound" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getUserPointsPerRound",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getUserPointsPerRound`


<!-- END_11e6e58e01a29d2940ac3a7cb2f01a61 -->

<!-- START_17cd97afbcd8934ec1ad53a10b386ccd -->
## Get current round

Getter for number of the current round in league // params: l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getCurrentRound" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getCurrentRound",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getCurrentRound`


<!-- END_17cd97afbcd8934ec1ad53a10b386ccd -->

<!-- START_95dfc177f9de4c316fec1721271bdf24 -->
## Get user squad

Getter for certain user squad with players, points, ranking stats // params: l_id (league id) , uuid (user's uuid)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getUserSquad" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getUserSquad",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getUserSquad`


<!-- END_95dfc177f9de4c316fec1721271bdf24 -->

<!-- START_54eec3cf5cd5d74a40d732ecfd72fbdf -->
## Get all leagues

Fetch all existing leagues

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getAllLeagues" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getAllLeagues",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getAllLeagues`

`HEAD api/getAllLeagues`


<!-- END_54eec3cf5cd5d74a40d732ecfd72fbdf -->

<!-- START_35db066d1238b840c02ddc9b7e7bd52c -->
## Get user&#039;s leagues

Fetch leagues logged in user is playing (jwt auth token required)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getMyLeagues" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getMyLeagues",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getMyLeagues`

`HEAD api/getMyLeagues`


<!-- END_35db066d1238b840c02ddc9b7e7bd52c -->

<!-- START_35ede6d9183af1006fb0ac00dcd67e04 -->
## Get news per league

Fetch all public published news per league (public=1,published=1) // params: l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getNews" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getNews",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getNews`


<!-- END_35ede6d9183af1006fb0ac00dcd67e04 -->

<!-- START_006eda37a2eca4db499394c1c422659b -->
## Get authorized user&#039;s squad points

Getter for user's squad points by rounds (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getAllPoints" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getAllPoints",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getAllPoints`

`HEAD api/getAllPoints`


<!-- END_006eda37a2eca4db499394c1c422659b -->

<!-- START_5733df7ad30def37354255332e38923d -->
## Get authorized user&#039;s ranking

Getter for user's ranking points in current and last round (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getUserRank" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getUserRank",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getUserRank`

`HEAD api/getUserRank`


<!-- END_5733df7ad30def37354255332e38923d -->

<!-- START_7fb234a1895617b57247550e2fe487e1 -->
## Get authorized user&#039;s ranking

Getter for user's ranking points in current and last round (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getRankingTable/{l_id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getRankingTable/{l_id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getRankingTable/{l_id}`

`HEAD api/getRankingTable/{l_id}`


<!-- END_7fb234a1895617b57247550e2fe487e1 -->

<!-- START_6cdcee0faf106915a4910687f3cf8449 -->
## Get single user&#039;s ranking

Getter for single user's ranking points in current,last round and avg on Ranking page // params: l_id (league id), uuid

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getUserStats" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getUserStats",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getUserStats`


<!-- END_6cdcee0faf106915a4910687f3cf8449 -->

<!-- START_4af9a5890d7177cc5a2ce45712f32f99 -->
## Get all user&#039;s ranking

Getter for all users` ranking points in current,last round and avg on Ranking page // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getUsersStats" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getUsersStats",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getUsersStats`

`HEAD api/getUsersStats`


<!-- END_4af9a5890d7177cc5a2ce45712f32f99 -->

<!-- START_b9d97074cbfd41c5f8b73797eab10b1d -->
## Send tip

Send tips(tickets) to admins in that league (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/sendTip" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/sendTip",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/sendTip`


<!-- END_b9d97074cbfd41c5f8b73797eab10b1d -->

<!-- START_62de2cd0712003a5a147ae64946c3e61 -->
## Get dream team on homepage

Getter for top 11 players in total and from last round (1 gk, 4 defs, 4 mid, 2 atk) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getDreamTeam" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getDreamTeam",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getDreamTeam`

`HEAD api/getDreamTeam`


<!-- END_62de2cd0712003a5a147ae64946c3e61 -->

<!-- START_186855dc270df8b0f94894a71c67e041 -->
## Get jersey image

Fetch image for club's jersey from local storage(clubs) // params: name (club name)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getJersey/{name}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getJersey/{name}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getJersey/{name}`

`HEAD api/getJersey/{name}`


<!-- END_186855dc270df8b0f94894a71c67e041 -->

<!-- START_78e3e94ef9975954075573942f12630c -->
## Get jersey image by id

Getter for image by club's id // params: id (club's id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getJerseyId/{$id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getJerseyId/{$id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getJerseyId/{$id}`

`HEAD api/getJerseyId/{$id}`


<!-- END_78e3e94ef9975954075573942f12630c -->

<!-- START_7232b999e72183b9afdfd93314bd6e19 -->
## Get news image

Getter for image from storage(news) // params: name

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getImage/{name}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getImage/{name}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getImage/{name}`

`HEAD api/getImage/{name}`


<!-- END_7232b999e72183b9afdfd93314bd6e19 -->

<!-- START_16a5f615a20f4137b9d3b8cf93d73484 -->
## Check if username or email exists

Used on registration, checking if user with username or email already exists  // params: username, email

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/checkUser" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/checkUser",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/checkUser`


<!-- END_16a5f615a20f4137b9d3b8cf93d73484 -->

<!-- START_af1fa752f6625323393c89d3bb10a609 -->
## Get all user&#039;s ranking

Getter for all users` ranking points in current,last round and avg on Ranking page // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getPlayerInfo" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getPlayerInfo",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getPlayerInfo`

`HEAD api/getPlayerInfo`


<!-- END_af1fa752f6625323393c89d3bb10a609 -->

<!-- START_8bdbf2cd244771fdfa883992a357c7b5 -->
## Get single match statistics

Getter for single match stats(score, assists, yellow and red cards) // params: l_id (league id), m_id (match id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/getMatchInfo" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getMatchInfo",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/getMatchInfo`


<!-- END_8bdbf2cd244771fdfa883992a357c7b5 -->

#Login

User login route
<!-- START_57e3b4272508c324659e49ba5758c70f -->
## Login route

User login function, returning token,user's info,role,leagues  // params: email, password

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/user/login" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/user/login",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/user/login`


<!-- END_57e3b4272508c324659e49ba5758c70f -->

#Payment

Payment routes: on registering, buying new league and unlocking a free league
<!-- START_deb129964c28500a2815c8b001f0bc2e -->
## Make a payment

Pre-registration function, make a payment on registration for a league // params: l_id (league id), stripeToken (from stripe form), amount (min:150), name (first name), lastName, email

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/payment" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/payment",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/payment`


<!-- END_deb129964c28500a2815c8b001f0bc2e -->

<!-- START_7e5fdc68d2de1941a9b87e12130aefd2 -->
## Make a payment for additional league

Make a payment to play a new league under settings tab (jwt auth token required)  // params: league (league id), amount(min 150)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/additionalPayment" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/additionalPayment",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/additionalPayment`


<!-- END_7e5fdc68d2de1941a9b87e12130aefd2 -->

<!-- START_d65e4e1aa1c9ef69bb6889aa12d52fc0 -->
## Unlock free league

Unlock free league under settings tab (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/unlockFreeLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/unlockFreeLeague",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/unlockFreeLeague`


<!-- END_d65e4e1aa1c9ef69bb6889aa12d52fc0 -->

#PrivateLeague

Private league functions
<!-- START_49c058a2ad897c4490f8bc0410d703fb -->
## Create private league

Create private league on League page (jwt auth token required) // params: l_id (league id), name (private league name)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/createLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/createLeague",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/createLeague`


<!-- END_49c058a2ad897c4490f8bc0410d703fb -->

<!-- START_87d9cdefae722bf6d2291f0aa37b2863 -->
## Get private leagues for authenticated user

Getter for all joined and created private leagues on League page (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/privateLeague/getPrivateLeagues" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/getPrivateLeagues",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/privateLeague/getPrivateLeagues`

`HEAD api/privateLeague/getPrivateLeagues`


<!-- END_87d9cdefae722bf6d2291f0aa37b2863 -->

<!-- START_dc4ec655f3be93f9291e26b043a04860 -->
## Leave a league

Leave joined private league (jwt auth token required) // params: id (id of private league)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/leaveLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/leaveLeague",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/leaveLeague`


<!-- END_dc4ec655f3be93f9291e26b043a04860 -->

<!-- START_74ffdee24831ec4678c6beb6376e6995 -->
## Delete a league

Delete a created private league. All users must be kicked first (jwt auth token required) // params: id (id of private league)

> Example request:

```bash
curl -X DELETE "https://test.boopro.breddefantasy.com//api/privateLeague/deleteLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/deleteLeague",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/privateLeague/deleteLeague`


<!-- END_74ffdee24831ec4678c6beb6376e6995 -->

<!-- START_f4b69f9619b0a7be956e808a5f1ceda9 -->
## Join a league

Join a  private league on League page, must be invited first (jwt auth token required) // params: code (code of private league)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/joinLeague" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/joinLeague",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/joinLeague`


<!-- END_f4b69f9619b0a7be956e808a5f1ceda9 -->

<!-- START_6091adf6f19a451df2e504cd077a940f -->
## Send an invite

Invite users to join your private leagues // params: code (code of private league), email (invited user's email)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/sendInvite" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/sendInvite",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/sendInvite`


<!-- END_6091adf6f19a451df2e504cd077a940f -->

<!-- START_364773c532ada92e54bf09a3a681ef60 -->
## Ban user from league

Ban user from your private league (jwt auth token required) // params: id (id of private league), email (user's email)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/banUser" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/banUser",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/banUser`


<!-- END_364773c532ada92e54bf09a3a681ef60 -->

<!-- START_0e359d8f1091b93fe9de7e6af688b602 -->
## Show private league table

Show private league table with total points and points for specific round // params: name (name of private league), gw (number of wanted round)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/showTable" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/showTable",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/showTable`


<!-- END_0e359d8f1091b93fe9de7e6af688b602 -->

<!-- START_a924f100f17cecb9c77109f78d448e0a -->
## Get the score table for private league

Invite users to join your private leagues // params: id (id of private league)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/privateLeague/getTable" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/getTable",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/privateLeague/getTable`


<!-- END_a924f100f17cecb9c77109f78d448e0a -->

<!-- START_371d9c867d5d224af285a8a23686aa3b -->
## Join a league from email

Join a private league from email invite, must be invited first // params: code (code of private league), email (invited user's email)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/privateLeague/joinLeague/{email}/{code}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/privateLeague/joinLeague/{email}/{code}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/privateLeague/joinLeague/{email}/{code}`

`HEAD api/privateLeague/joinLeague/{email}/{code}`


<!-- END_371d9c867d5d224af285a8a23686aa3b -->

#Registration

User registration routes
<!-- START_638687f1aca2f1e69b360d1516c7c1f9 -->
## Registration route

User login function, returning token,user's info,role,leagues  // params: first_name, last_name, username, country, email. city, address, zip, phone, password

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/user/register" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/user/register",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/user/register`


<!-- END_638687f1aca2f1e69b360d1516c7c1f9 -->

#Squad

User squads routes, My Team &amp; Transfer page controller
<!-- START_d06c71c2583429d6ab2a372f94c109be -->
## Get my team

Getter for all info(user,ranking,squad with players,current round deadline,squad value) on My team page for authorized user (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/myTeam/getMyTeamPage" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/getMyTeamPage",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/myTeam/getMyTeamPage`

`HEAD api/myTeam/getMyTeamPage`


<!-- END_d06c71c2583429d6ab2a372f94c109be -->

<!-- START_bffe65e2a370d47c6b179eda02d60ded -->
## Update user squad

Update user's squad on My team page or after resetting team (jwt auth token required) // params: l_id (league id), selected_team(array[11] of ids of starting 11), substitutions(array[4] of ids of 4 subs), name (optional, squad name), captain (optional, id of captain)

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/myTeam/updateSquad" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/updateSquad",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/myTeam/updateSquad`


<!-- END_bffe65e2a370d47c6b179eda02d60ded -->

<!-- START_f40762f350d75620cb0e1101bb03903b -->
## Post user squad

Create user's squad after registration (jwt auth token required) // params: l_id (league id), selected_team(array[11] of ids of starting 11), substitutions(array[4] of ids of 4 subs), name (optional, squad name)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/myTeam/postSquad" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/postSquad",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/myTeam/postSquad`


<!-- END_f40762f350d75620cb0e1101bb03903b -->

<!-- START_10f2af451f0b155a365f19dd578dae8e -->
## Make transfer

Sell and buy player(s) on Transfer page (jwt auth token required) // params: l_id (league id), buy(array of ids of bought players),sell(array of ids of selled players)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/myTeam/makeTransfer" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/makeTransfer",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/myTeam/makeTransfer`


<!-- END_10f2af451f0b155a365f19dd578dae8e -->

<!-- START_b8858826e331a2c5c8d076fc72ac98d4 -->
## Check transfers

Check number of free transfers left, get how many points was cost of last transfers (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/myTeam/checkTransfer" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/checkTransfer",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/myTeam/checkTransfer`


<!-- END_b8858826e331a2c5c8d076fc72ac98d4 -->

<!-- START_8d84111109d4465824ee14fb4ed490b6 -->
## Check squad

Check if user's squad has bought 15 players (jwt auth token required) // params: l_id (league id)

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/myTeam/hasSquad" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/hasSquad",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/myTeam/hasSquad`


<!-- END_8d84111109d4465824ee14fb4ed490b6 -->

<!-- START_0cd4d97836741d8afd6cff54cc5e822b -->
## Get all players with stats

Getter for all footballers with stats on Transfer and Statistics pages // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/myTeam/getPlayers" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/getPlayers",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/myTeam/getPlayers`

`HEAD api/myTeam/getPlayers`


<!-- END_0cd4d97836741d8afd6cff54cc5e822b -->

<!-- START_8bc7860358a0c77a3edb179d96bd1102 -->
## Get next round

Get next round with matches // params: l_id (league id)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/myTeam/getNextRound" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/myTeam/getNextRound",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/myTeam/getNextRound`

`HEAD api/myTeam/getNextRound`


<!-- END_8bc7860358a0c77a3edb179d96bd1102 -->

#User

User account routes
<!-- START_3dc890edc904c78e207d820744790aa8 -->
## Get authorized user&#039;s info

Getter for logged in user's information under settings component (jwt auth token required)

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getUserSettings" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getUserSettings",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getUserSettings`

`HEAD api/getUserSettings`


<!-- END_3dc890edc904c78e207d820744790aa8 -->

<!-- START_f95d40de3d33b2a30bec4585dc47f980 -->
## Update authorized user&#039;s info

Update logged in user's information under settings component (jwt auth token required) // params(optional): birthdate,country,phone,address,city,zip,first_name,last_name

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/updateUserSettings" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/updateUserSettings",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/updateUserSettings`


<!-- END_f95d40de3d33b2a30bec4585dc47f980 -->

<!-- START_87d890d40e8ab08031500733c67c8649 -->
## Update authorized user&#039;s password

Update logged in user's password under settings component (jwt auth token required) // params: old_password, new_password, new_password_confirm

> Example request:

```bash
curl -X PUT "https://test.boopro.breddefantasy.com//api/changePassword" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/changePassword",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/changePassword`


<!-- END_87d890d40e8ab08031500733c67c8649 -->

<!-- START_c49a859203a01ef1cd7f26cd8ee8e76d -->
## Send reset password link

Send reset password link to user // params: email

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/sendResetPassword" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/sendResetPassword",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/sendResetPassword`


<!-- END_c49a859203a01ef1cd7f26cd8ee8e76d -->

<!-- START_32fd52d5dcaeb855370670debd8a4b68 -->
## Confirm new password

Confirm new password after clicking on link from email // params: email,new_password,new_password_confirm

> Example request:

```bash
curl -X POST "https://test.boopro.breddefantasy.com//api/confirmPassword" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/confirmPassword",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/confirmPassword`


<!-- END_32fd52d5dcaeb855370670debd8a4b68 -->

<!-- START_55c7c0313b15c789e11d401d706db432 -->
## Check if reset link is still in use

Check if user is able to change password // params: token

> Example request:

```bash
curl -X GET "https://test.boopro.breddefantasy.com//api/getNewPassword/{token}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://test.boopro.breddefantasy.com//api/getNewPassword/{token}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/getNewPassword/{token}`

`HEAD api/getNewPassword/{token}`


<!-- END_55c7c0313b15c789e11d401d706db432 -->

