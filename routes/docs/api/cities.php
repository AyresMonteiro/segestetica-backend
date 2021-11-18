<?php

//---------------------------------- DATA REQUEST BODY OBJECT
/**
 *	@OA\Schema(
 *		title="City Data Request Body",	 
 * 		schema="CityDataRequestBody",
 * 		@OA\Property(
 * 			property="cityName",
 * 			description="Name of city.",
 * 			example="São Paulo",
 * 			type="string",
 *      	pattern="/^(\p{L}| )+$/u",
 * 		),
 * 		@OA\Property(
 * 			property="cityCityId",
 * 			description="City's City Identifier.",
 * 			example=255,
 * 			type="integer",
 * 		),
 *	)
 */

//---------------------------------- INDEX
/** @OA\Get(
 *      path="/api/cities",
 * 		description="List cities. Filters below can be applied.",
 *      tags={"cities"},
 *      @OA\Parameter(
 *          name="cityNameSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside name attribute.",
 *      ),
 *      @OA\Parameter(
 *          name="cityCityId",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="integer",
 *          ),
 *          description="City's City Id Foreign.",
 *      ),
 *      @OA\Parameter(
 *          name="cityCreatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities created after a given date.",
 *      ),
 *      @OA\Parameter(
 *          name="cityCreatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities created before a given date.",
 *      ),
 *      @OA\Parameter(
 *          name="cityUpdatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities last updated after a given date.",
 *      ),
 *      @OA\Parameter(
 *          name="cityUpdatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities last updated before a given date.",
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Cities successfully listed.",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/City")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *  )
 */

//---------------------------------- SHOW
/** 
 *  @OA\Get(
 *      path="/api/cities/{id}",
 * 		description="Shows a city by a given identifier.",
 *      tags={"cities"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="City Identifier.",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="bigint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="City has been found.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/City",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not found error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *  )
 */

//---------------------------------- STORE
/**
 *  @OA\Post(
 *      path="/api/cities",
 *      tags={"cities"},
 * 		description="Stores a new city.",
 *      @OA\RequestBody(
 *          description="City data to be stored.",
 *          @OA\JsonContent(ref="#/components/schemas/CityDataRequestBody"),
 *      ),
 *      @OA\Response(
 *          response="201",
 *          description="City successfully created.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/City"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *  )
 */

//---------------------------------- UPDATE
/**
 *  @OA\Put(
 *      path="/api/cities/{id}",
 *      tags={"cities"},
 * 		description="Updates a city by a given identifier.",
 * 		@OA\Parameter(
 *			name="id",
 *			in="path",
 *			description="City Identifier.",
 *			required=true,
 *			@OA\Schema(
 *			    type="integer",
 *			    format="tinyint"
 *			),
 * 		),
 *      @OA\RequestBody(
 *			description="New city data. You must send at least one of these attributes below.",
 *			@OA\JsonContent(ref="#/components/schemas/CityDataRequestBody"),
 *      ),
 *      @OA\Response(
 *          response="200",
 *          description="City successfully updated.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/City"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *  )
 */

//---------------------------------- DELETE
/** 
 *  @OA\Delete(
 *      path="/api/cities/{id}",
 *      tags={"cities"},
 * 		description="Deletes a city by a given identifier.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="City Identifier.",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="tinyint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="City has been deleted.",
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not found error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *  )
 */
