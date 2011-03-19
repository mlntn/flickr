Lightweight Flickr API frontend
===============================

Example usage
-------------

`$flickr     = new Flickr(FLICKR_API_KEY);`
`$photos     = $flickr->getPhotosByUser(FLICKR_USER_ID, 40, array('url_sq','owner_name'));`
`$photosets  = $flickr->getSetsByUser(FLICKR_USER_ID);`
