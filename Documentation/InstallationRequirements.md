# Installation Requirements

There is no test to validate this requirements for now.

## Webserver

Requirements:
- SSH Access with public-keys
- A Webserver (Apache or Nginx)
- A Database (MySQL or MariaDB).
- PHP >= 7.0.0 (make sure the PHP CLI has the same version)
  - PHP modules mbstring, tokenizer and pdo_mysql
  - PHP functions system(), shell_exec(), escapeshellcmd() and escapeshellarg()
  - One of the PHP modules imagick or gmagick
  - PHP memory_limit > 500MB

Source: 
- http://neos.readthedocs.io/en/3.0/GettingStarted/Installation.html#requirements

## Elastic Search

For feast search in large Datasets with fulltext Indexing or complex Queries.

Requirements:
- Elasticsearch versions 2.0.x to 2.4.x
- Elastic-Settings as described here https://github.com/Flowpack/Flowpack.ElasticSearch.ContentRepositoryAdaptor/blob/master/Documentation/ElasticConfiguration-2.0-2.4.md

Source:
- https://github.com/Flowpack/Flowpack.ElasticSearch.ContentRepositoryAdaptor

## Redis - Caching

A caching backend which stores cache entries in Redis using the phpredis PHP extension.
Redis is a noSQL database with very good scaling characteristics
in proportion to the amount of entries and data size.

Requirements:
- Redis 2.6.0+ (tested with 2.6.14 and 2.8.5)
- phpredis with Redis 2.6 support, e.g. 2.2.4 (tested with 92782639b0329ff91658a0602a3d816446a3663d from 2014-01-06)

Source:
- http://redis.io/
- https://github.com/nicolasff/phpredis
- https://github.com/neos/flow-development-collection/blob/4.0/Neos.Cache/Classes/Backend/RedisBackend.php#L19-L44
