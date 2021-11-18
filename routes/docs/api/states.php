<?php

//---------------------------------- INDEX
/**
 *  @OA\Get(
 *      path="/api/states",
 *      tags={"states"},
 *      @OA\Parameter(
 *          name="stateNameSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside name attribute",
 *      ),
 *      @OA\Parameter(
 *          name="stateAbbreviationSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside abbreviation attribute",
 *      ),
 *      @OA\Parameter(
 *          name="stateCreatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states created after a given date",
 *      ),
 *      @OA\Parameter(
 *          name="stateCreatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states created before a given date",
 *      ),
 *      @OA\Parameter(
 *          name="stateUpdatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states last updated after a given date",
 *      ),
 *      @OA\Parameter(
 *          name="stateUpdatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies states last updated before a given date",
 *      ),
 *      @OA\Response(
 *          response="200",
 *          description="List all states",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/State")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error",
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
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="State Identifier",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="bigint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="State has been found",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/State",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not found error",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error",
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
 *      path="/api/states/",
 *      tags={"states"},
 *      @OA\Response(
 *          response="201",
 *          description="State successfully created",
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Request error",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/ErrorResponse"
 *          ),
 *      ),
 *  )
 */
