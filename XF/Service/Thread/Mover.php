<?php

namespace Truonglv\QuotePostField\XF\Service\Thread;

use XF\Entity\Thread;
use Truonglv\QuotePostField\Util\FieldsRender;

class Mover extends XFCP_Mover
{
    /**
     * @var bool
     */
    protected $flagQuotePostFieldEnabled = true;

    /**
     * @param \XF\Entity\Forum $forum
     * @return mixed
     * @throws \XF\PrintableException
     */
    public function move(\XF\Entity\Forum $forum)
    {
        if ($this->flagQuotePostFieldEnabled) {
            $this->addExtraSetup(function (Thread $thread) use ($forum) {
                $post = $thread->FirstPost;

                $message = $post->message;

                $before = FieldsRender::render($thread, 'before', $forum);
                if (\strlen($before) > 0) {
                    $message = $before . "\n\n" . $message;
                }

                $after = FieldsRender::render($thread, 'after', $forum);
                if (\strlen($after) > 0) {
                    $message = $message . "\n\n" . $after;
                }

                if ($message !== $post->message) {
                    $post->message = $message;
                    $thread->addCascadedSave($post);
                }
            });
        }

        return parent::move($forum);
    }
}
