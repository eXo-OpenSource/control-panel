<?php


namespace App\Paginator;


class CursorPaginator
{
    protected $items;
    protected $nextCursor;
    protected $params = [];

    public function __construct($items, $nextCursor = null)
    {
        $this->items = $items;
        $this->nextCursor = $nextCursor;
    }

    public static function currentCursor()
    {
        return json_decode(base64_decode(request('cursor')));
    }

    public function appends($params)
    {
        $this->params = $params;

        return $this;
    }

    public function items()
    {
        return $this->items;
    }

    public function nextCursorUrl()
    {
        return $this->nextCursor ? url()->current().'?'.http_build_query(array_merge([
                'cursor' => base64_encode(json_encode($this->nextCursor)),
            ], $this->params)) : null;
    }
}
