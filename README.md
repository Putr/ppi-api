Pirate Party International API
==============================

USAGE:
------

Returns PPI data thrugh:
 * /v1/getPirateParties/json
 * /v1/getPirateParties/xml

NGINX rewrite:

		location / {
                try_files $uri @site;
        }

        location @site {
                rewrite ^ /index.php?q=$uri&$args last;
        }


FUTURE:
-------

 * Thiking about implementing a way for parties to update this information themselfes. (http:/partyurl.org/pp.info or /pp-info)
 * Move data to DB
 * Expand number of data points, different apis
 * Implement at ppi domain
 * URGENT: Generate static files

HELP NEEDED:
------------

 * Collect all PP Logos, save as PNGs and named like COUNTRYCODE.png (SI.png) (as banners)
 * More data about pirate partys (blogs, reps, etc) in a CSV format so it can be parsed
