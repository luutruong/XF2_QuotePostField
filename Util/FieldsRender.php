<?php

namespace Truonglv\QuotePostField\Util;

use XF\Entity\Thread;
use XF\Html\Renderer\BbCode;

class FieldsRender
{
    /**
     * @param Thread $thread
     * @param string $group
     * @return string
     */
    public static function render(Thread $thread, $group)
    {
        $templater = \XF::app()->templater();
        $globalParams = \XF::app()->getGlobalTemplateData();

        $templater->addDefaultParam('xf', $globalParams);

        $html = $templater->renderMacro(
            'public:qpf_custom_fields_macros',
            'custom_fields_values',
            [
                'type' => 'threads',
                'group' => $group,
                'onlyInclude' => $thread->Forum->field_cache,
                'set' => $thread->custom_fields,
                'valueClass' => 'message-fields message-fields--' . $group
            ]
        );

        $bbCode = BbCode::renderFromHtml($html);

        return \trim($bbCode);
    }
}
