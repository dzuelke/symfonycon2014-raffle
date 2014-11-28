<?php

$mongodb_url = getenv('MONGODB_URL') ?: getenv('MONGOHQ_URL');
$container->setParameter('env.mongodb_url', $mongodb_url);
$container->setParameter('env.mongodb_database', substr(parse_url($mongodb_url, PHP_URL_PATH), 1));

?>