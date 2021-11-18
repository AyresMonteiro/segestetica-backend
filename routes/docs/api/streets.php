<?php

//---------------------------------- INDEX
/** @OA\Get(
 *      path="/api/streets",
 *      tags={"streets"},
 *      @OA\Parameter(
 *          name="streetNameSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside name attribute",
 *      ),
 *      @OA\Parameter(
 *          name="streetPostCodeSearch",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *          ),
 *          description="A string to be searched inside postCode attribute",
 *      ),
 *      @OA\Parameter(
 *          name="streetCreatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies streets created after a given date",
 *      ),
 *      @OA\Parameter(
 *          name="streetCreatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies streets created before a given date",
 *      ),
 *      @OA\Parameter(
 *          name="streetUpdatedAtGreaterThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies streets last updated after a given date",
 *      ),
 *      @OA\Parameter(
 *          name="streetUpdatedAtLesserThan",
 *          in="query",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              format="date-time",
 *          ),
 *          description="Specifies streets last updated before a given date",
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Streets successfully listed",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Street")
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
