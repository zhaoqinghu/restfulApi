<?php
 $app->get('/domains/{domainId}', get_domain_info);
 $app->post('/domains', domain_add);