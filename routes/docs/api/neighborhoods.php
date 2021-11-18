<?php

//---------------------------------- INDEX
/**
 *  @OA\Get(
 *      path="/api/neighborhoods/",
 *      tags={"neighborhoods"},
 *      @OA\Response(
 *          response="200",
 *          description="List all neighborhoods",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Neighborhood")
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
 *      path="/api/neighborhoods/{id}",
 *      tags={"neighborhoods"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="Neighborhood Identifier",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="bigint"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Shown Neighborhood successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/City",
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Server error",
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
