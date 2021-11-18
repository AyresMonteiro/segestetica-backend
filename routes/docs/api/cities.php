<?php

//---------------------------------- INDEX
/** @OA\Get(
 *      path="/api/cities",
 *      tags={"cities"},
 *      @OA\Parameter(
 *          name="cityNameSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside name attribute",
 *      ),
 *      @OA\Parameter(
 *          name="cityStateId",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="integer",
 *          ),
 *          description="City's State Id Foreign",
 *      ),
 *      @OA\Parameter(
 *          name="cityCreatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities created after a given date",
 *      ),
 *      @OA\Parameter(
 *          name="cityCreatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities created before a given date",
 *      ),
 *      @OA\Parameter(
 *          name="cityUpdatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities last updated after a given date",
 *      ),
 *      @OA\Parameter(
 *          name="cityUpdatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies cities last updated before a given date",
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Cities successfully listed",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/City")
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
 *      path="/api/cities/{id}",
 *      tags={"cities"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="City Identifier",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="bigint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="City has been found",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/City",
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
