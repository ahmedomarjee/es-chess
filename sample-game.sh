#!/usr/bin/env bash

PLAYER_ONE='Jan-Krzysztof Duda'
PLAYER_TWO='Magnus Carlsen'
GAME_NAME='Altibox Norway Chess'

php bin/console chess:start  "$PLAYER_ONE" "$PLAYER_TWO" "$GAME_NAME"

read -p "Enter game id: " GAME_ID

sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_ONE" e4
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_TWO" c6
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_ONE" d4
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_TWO" d5
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_ONE" Nc3
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_TWO" dxe4
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_ONE" Nxe4
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_TWO" Nf6
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_ONE" Nxf6+
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_TWO" exf6
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_ONE" c3
sleep 1
php bin/console chess:move $GAME_ID "$PLAYER_TWO" Bd6
