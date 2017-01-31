<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAll()
    {
        return Post::paginate();
    }

    /**
     * Process post to create.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'author_id' => 'required'
        ]);

        // TODO
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getById($id)
    {
        $resource = Post::find($id);
        if ($resource === null) {
            abort(404, "Resource not found");
        }
        return $resource;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Patch the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function patch($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $resource = Post::find($id);
        if ($resource === null) {
            abort(404, "Resource not found");
        }
        return $resource->delete();
    }
}
