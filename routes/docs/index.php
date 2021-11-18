<?php

/**
 *  @OA\Info(title="SegEstética API", version="1.0.0", contact={"email":"ayresmonteiro52@gmail.com"})
 * 
 *  @OA\Schema(
 *      title="Example Error Response",
 *      schema="ErrorResponse",
 *      type="object",
 *      @OA\Property(
 *          property="errors",
 *          type="array",
 *          @OA\Items(
 *              type="string", description="Error string", example="This is an error string example. There are no line-breaks."
 *          ),
 *      ),
 *  )
 * 
 */
