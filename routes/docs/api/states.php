<?php

//---------------------------------- DATA REQUEST BODY OBJECT
/**
 *	@OA\Schema(
 *		title="State Data Request Body",	 
 * 		schema="StateDataRequestBody",
 * 		@OA\Property(
 * 			property="stateName",
 * 			description="Name of state.",
 * 			example="São Paulo",
 * 			type="string",
 *      	pattern="/^(\p{L}| )+$/u",
 * 		),
 * 		@OA\Property(
 * 			property="stateAbbreviation",
 * 			description="Abbreviation of state.",
 * 			example="SP",
 * 			type="string",
 *      	pattern="/^[A-Z]{2,3}$/",
 * 		),
 *	)
 */

//---------------------------------- INDEX
/**
 *  @OA\Get(
 *      path="/api/states",
 *      tags={"states"},
 * 		description="List states. Filters below can be applied.",
 *      @OA\Parameter(
 *  		name="stateNameSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside name attribute.",
 *      ),
 *      @OA\Parameter(
 *          name="stateAbbreviationSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 * 				pattern="/^[A-Z]{1,3}$/",
 *          ),
 *          description="A string to be searched inside abbreviation attribute.",
 *      ),
 *      @OA\Parameter(
 *          name="stateCreatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states created after a given date.",
 *      ),
 *      @OA\Parameter(
 *          name="stateCreatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states created before a given date.",
 *      ),
 *      @OA\Parameter(
 *          name="stateUpdatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states last updated after a given date.",
 *      ),
 *      @OA\Parameter(
 *          name="stateUpdatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states last updated before a given date.",
 *      ),
 *      @OA\Response(
 *          response="200",
 *          description="List all states.",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/State")
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
 *      path="/api/states/{id}",
 *      tags={"states"},
 * 		description="Shows a state by a given identifier.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="State Identifier.",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="tinyint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="State has been found.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/State",
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
 *      path="/api/states",
 *      tags={"states"},
 * 		description="Stores a new state.",
 *      @OA\RequestBody(
 *          description="State data to be stored.",
 *          @OA\JsonContent(ref="#/components/schemas/StateDataRequestBody"),
 *      ),
 *      @OA\Response(
 *          response="201",
 *          description="State successfully created.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/State"
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
 *      path="/api/states/{id}",
 *      tags={"states"},
 * 		description="Updates a state by a given identifier.",
 * 		@OA\Parameter(
 *			name="id",
 *			in="path",
 *			description="State Identifier.",
 *			required=true,
 *			@OA\Schema(
 *			    type="integer",
 *			    format="tinyint"
 *			),
 * 		),
 *      @OA\RequestBody(
 *			description="New state data. You must send at least one of these attributes below.",
 *			@OA\JsonContent(ref="#/components/schemas/StateDataRequestBody"),
 *      ),
 *      @OA\Response(
 *          response="200",
 *          description="State successfully updated.",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/State"
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
 *      path="/api/states/{id}",
 *      tags={"states"},
 * 		description="Deletes a state by a given identifier.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="State Identifier.",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="tinyint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="State has been deleted.",
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
