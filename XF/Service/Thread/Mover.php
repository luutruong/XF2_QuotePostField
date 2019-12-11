<?php

namespace Truonglv\QuotePostField\XF\Service\Thread;

use XF\Entity\Thread;
use Truonglv\QuotePostField\Util\FieldsRender;

class Mover extends XFCP_Mover
{
    /**
     * @var bool
     */
    private $flagQuotePostFieldEnabled = true;

    /**
     * @param bool $flagQuotePostFieldEnabled
     * @return void
     */
    public function setFlagQuotePostFieldEnabled(bool $flagQuotePostFieldEnabled)
    {
        $this->flagQuotePostFieldEnabled = $flagQuotePostFieldEnabled;
    }

    /**
     * @param \XF\Entity\Forum $forum
     * @return mixed
     */
    public function move(\XF\Entity\Forum $forum)
    {
        if ($this->flagQuotePostFieldEnabled) {
            $this->addExtraSetup(function (Thread $thread) {
                $post = $thread->FirstPost;

                $message = $post->message;

                $before = FieldsRender::render($thread, 'before');
                if (\strlen($before) > 0) {
                    $message = $before . "\n\n" . $message;
                }

                $after = FieldsRender::render($thread, 'after');
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
