<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API OAuth Token
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services, 
    | unless overwritten bellow using 'token' parameter
    |
    |
    */
    
    'token'       => 'YOUR API OAUTH TOKEN HERE',
    'secret'      => 'YOUR API CLIENT SECRET HERE',

    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services to verify 
    | SSL peer (SSL certificate validation)
    |
     */

    'ssl_verify_peer' => FALSE,

    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    | url - web service URL
    | type - request type POST or GET
    | token - API OAuth token, if different to token above
    | responseDefaultKey - specify default field value to be retruned when calling getByKey()
    | param - accepted request parameters
    |
    */
    'url'           => 'https://www.eventbriteapi.com/v3',

    'service'       => [

        /**
         * POST /batch/
         * 
         * Batched requests currently execute serially on our servers, rather than in 
         * parallel, so they are not suitable for speeding up a set of very slow operations, 
         * but are much better for a large volume of quick operations.
         */
        'batchrequest' => [
            'endpoint'              => '/batch/',
            'type'                  => 'POST',
            'token'                 => null,
            'responseDefaultKey'    => null,
            'param'                 => []
        ],

        
        /**
         * GET /events/search/
         * 
         * Allows you to retrieve a paginated response of public event objects from across 
         * Eventbrite’s directory, regardless of which user owns the event.
         */
        'search' => [
            'endpoint'              => '/events/search/',
            'type'                  => 'GET',
            'token'                 => null,
            'responseDefaultKey'    => 'events',
            'param'                 => [
                                        'q'                 => null,
                                        'sort_by'           => null,
                                        'location'          => [
                                            'address'           => null,
                                            'within'            => null,
                                            'latitude'          => null,
                                            'longitude'         => null,
                                            'viewport'          => [
                                                'northeast'         => [
                                                    'latitude'          => null,
                                                    'longitude'         => null,
                                                ],
                                                'southwest'         => [
                                                    'latitude'          => null,
                                                    'longitude'         => null,
                                                ]
                                            ]
                                        ],
                                        'organizer'         => [
                                            'id'                => null,
                                        ],
                                        'user'              => [
                                            'id'                => null,
                                        ],
                                        'tracking_code'     => null,
                                        'categories'        => null,
                                        'subcategories'     => null,
                                        'formats'           => null,
                                        'price'             => null,
                                        'start_date'        => [
                                            'range_start'       => null,
                                            'range_end'         => null,
                                            'keyword'           => null,
                                        ],
                                        'date_modified'     => [
                                            'range_start'       => null,
                                            'range_end'         => null,
                                            'keyword'           => null,
                                        ],
                                        'search_type'       => null,
                                        'include_all_series_instances'  => null,
                                        'include_unavailable_events'    => null,
                                        'incorporate_user_affinities'   => null,
                                        'high_affinity_categories'      => null,
                                        ]
        ],


        /**
         * POST /events/
         * 
         * Makes a new event, and returns an event for the specified event. Does not 
         * support the creation of repeating event series.
         */
        'create-event' => [
            'endpoint'              => '/events/',
            'type'                  => 'POST',
            'token'                 => null,
            'responseDefaultKey'    => null,
            'param'                 => [
                                        'event'             => [
                                            'name'              => [
                                                'html'              => null,
                                            ],
                                            'description'       => [
                                                'html'              => null,
                                            ],
                                            'organizer_id'      => null,
                                            'start'             => [
                                                'utc'               => null,
                                                'timezone'          => null,
                                            ],
                                            'end'               => [
                                                'utc'               => null,
                                                'timezone'          => null,
                                            ],
                                            'hide_start_date'   => null,
                                            'hide_end_date'     => null,
                                            'currency'          => null,
                                            'venue_id'          => null,
                                            'online_event'      => null,
                                            'listed'            => null,
                                            'logo'              => [
                                                'id'                => null,
                                            ],
                                            'logo_id'           => null,
                                            'category_id'       => null,
                                            'subcategory_id'    => null,
                                            'format_id'         => null,
                                            'shareable'         => null,
                                            'invite_only'       => null,
                                            'password'          => null,
                                            'capacity'          => null,
                                            'show_remaining'    => null,
                                            'source'            => null,
                                        ],
                                        ]
        ],


        /**
         * GET /events/:id/
         * 
         * Returns an event for the specified event. Many of Eventbrite’s API use 
         * cases revolve around pulling details of a specific event within an Eventbrite 
         * account. Does not support fetching a repeating event series parent 
         * (see GET /series/:id/).
         */
        'event' => [
            'endpoint'              => '/events/:id/',
            'type'                  => 'GET',
            'token'                 => null,
            'responseDefaultKey'    => null,
            'param'                 => null
        ],


        /**
         * POST /events/:id/
         * 
         * Updates an event. Returns an event for the specified event. Does not support 
         * updating a repeating event series parent (see POST /series/:id/).
         */
        'update-event' => [
            'endpoint'              => '/events/:id/',
            'type'                  => 'POST',
            'token'                 => null,
            'responseDefaultKey'    => null,
            'param'                 => [
                                        'event'             => [
                                            'name'              => [
                                                'html'              => null,
                                            ],
                                            'description'       => [
                                                'html'              => null,
                                            ],
                                            'organizer_id'      => null,
                                            'start'             => [
                                                'utc'               => null,
                                                'timezone'          => null,
                                            ],
                                            'end'               => [
                                                'utc'               => null,
                                                'timezone'          => null,
                                            ],
                                            'hide_start_date'   => null,
                                            'hide_end_date'     => null,
                                            'currency'          => null,
                                            'venue_id'          => null,
                                            'online_event'      => null,
                                            'listed'            => null,
                                            'logo'              => [
                                                'id'                => null,
                                            ],
                                            'logo_id'           => null,
                                            'category_id'       => null,
                                            'subcategory_id'    => null,
                                            'format_id'         => null,
                                            'shareable'         => null,
                                            'invite_only'       => null,
                                            'password'          => null,
                                            'capacity'          => null,
                                            'show_remaining'    => null,
                                            'source'            => null,
                                        ],
                                        ]
        ],


        /**
         * POST /events/:id/publish/
         * 
         * Publishes an event if it has not already been deleted. In order for publish 
         * to be permitted, the event must have all necessary information, including a 
         * name and description, an organizer, at least one ticket, and valid payment 
         * options. 
         * This API endpoint will return argument errors for event fields that fail to 
         * validate the publish requirements. Returns a boolean indicating success or 
         * failure of the publish.
         */
        'publish-event' => [
            'endpoint'              => '/events/:id/publish/',
            'type'                  => 'POST',
            'token'                 => null,
            'responseDefaultKey'    => null,
            'param'                 => null
        ],


        /**
         * POST /events/:id/unpublish/
         * 
         * Unpublishes an event. In order for a free event to be unpublished, it must 
         * not have any pending or completed orders, even if the event is in the past. 
         * In order for a paid event to be unpublished, it must not have any pending or 
         * completed orders, unless the event has been completed and paid out. Returns 
         * a boolean indicating success or failure of the unpublish.
         */
        'unpublish-event' => [
            'endpoint'              => '/events/:id/unpublish/',
            'type'                  => 'POST',
            'token'                 => null,
            'responseDefaultKey'    => null,
            'param'                 => null
        ],

    ],

];
