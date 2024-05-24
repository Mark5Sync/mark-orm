<?php


use markorm\migrations\MigrationGenerator;

require '../vendor/autoload.php';




// Пример использования
$jsonData = '{
    "tables": [
    {
      "name": "users",
      "position": [
        364.11865130575393,
        -1167.619550276636
      ],
      "colls": [
        {
          "field": "id",
          "type": "INT",
          "allowNull": false,
          "primaryKey": true,
          "default": null,
          "autoIncrement": true,
          "relation": null
        },
        {
          "field": "name",
          "type": "STRING",
          "allowNull": false,
          "primaryKey": false,
          "default": null,
          "autoIncrement": false,
          "relation": null
        },
        {
          "field": "email",
          "type": "STRING",
          "allowNull": false,
          "primaryKey": false,
          "default": null,
          "autoIncrement": false,
          "relation": null
        },
        {
          "field": "phone",
          "type": "STRING",
          "allowNull": true,
          "primaryKey": false,
          "default": null,
          "autoIncrement": false,
          "relation": null
        },
        {
          "field": "password_hash",
          "type": "STRING",
          "allowNull": false,
          "primaryKey": false,
          "default": null,
          "autoIncrement": false,
          "relation": null
        },
        {
          "field": "avatar",
          "type": "STRING",
          "allowNull": true,
          "primaryKey": false,
          "default": null,
          "autoIncrement": false,
          "relation": null
        }
      ]
    },
    {
        "name": "baskets",
        "position": [
          2858.781365383316,
          1044.0553195072707
        ],
        "colls": [
          {
            "field": "id",
            "type": "INT",
            "allowNull": false,
            "primaryKey": true,
            "default": null,
            "autoIncrement": true,
            "relation": null
          },
          {
            "field": "title",
            "type": "STRING",
            "allowNull": true,
            "primaryKey": false,
            "default": null,
            "autoIncrement": false,
            "relation": null
          },
          {
            "field": "userId",
            "type": "INT",
            "allowNull": true,
            "primaryKey": false,
            "default": null,
            "autoIncrement": false,
            "relation": {
              "table": "users",
              "coll": "id",
              "onDelete": "cascade",
              "onUpdate": null
            }
          },
          {
            "field": "price",
            "type": "FLOAT",
            "allowNull": true,
            "primaryKey": false,
            "default": null,
            "autoIncrement": false,
            "relation": null
          }
        ]
    }
]
}';



$arrayData = json_decode($jsonData, true);
new MigrationGenerator($arrayData['tables'], './migrations');