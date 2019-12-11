<?php

namespace Truonglv\QuotePostField\XF\Entity;

use Truonglv\QuotePostField\Util\FieldsRender;

class Post extends XFCP_Post
{
    /**
     * @param mixed $inner
     * @return mixed
     */
    public function getQuoteWrapper($inner)
    {
        if ($this->isFirstPost()) {
            $thread = $this->Thread;

            $beforeBbCode = FieldsRender::render($thread, 'before');
            if (\strlen($beforeBbCode) > 0) {
                $inner = $beforeBbCode . "\n\n" . $inner;
            }

            $afterBbCode = FieldsRender::render($thread, 'after');
            if (\strlen($afterBbCode) > 0) {
                $inner = $inner . "\n\n" . $afterBbCode;
            }
        }

        return parent::getQuoteWrapper($inner);
    }
}
