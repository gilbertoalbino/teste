# Statement

Serasa Experian has a selection process open for a PHP Developer opportunity.

The candidate who achieves the highest score will be selected.

# Installation

You should run `composer install` in the root directory inside the folder you cloned this repository.

# Testing

This application has been tested with Google Chrome's POST Man extension.

# API Endpoints

The API has the following endpoints:

## GET /api/candidates/index
Will retrieve all candidates rows.

## GET /api/candidates/{ID}
Will retrieve a single candidate row.

## POST /api/candidates
Will create a new candidate row.

## PUT /api/candidates/{ID}
Will update/patch a candidate data.

## DELETE /api/candidates/{ID}
Will delete a candidate row.

# JWT Authentication 

The API uses a JSON Web Token Authentication system.

So, in order for you to access the endpoints you should first generate a Token by requesting `/api/auth/login` URI.
A key named `token` is provided for ease.

## Bearer Header

Once  you have a token you should provide a `Bearer` header in your requests, as is:

`Authentication: Bearer GENERATED_TOKEN_HERE`

# Default Settings

You should visit the `app/config/parameters.php` file and define your environment settings.


# Default API Users

You should implement your Users Creation processes, but beforehand you can use the default user provided:

`user = user1`

`pass = user1`

**P.S.**: you'd better remove them on production.


