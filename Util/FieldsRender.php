<?php

namespace Truonglv\QuotePostField\Util;

use XF\Entity\Forum;
use XF\Entity\Thread;
use XF\Html\Renderer\BbCode;

class FieldsRender
{
    /**
     * @param Thread $thread
     * @param string $group
     * @param mixed $context
     * @return string
     */
    public static function render(Thread $thread, $group, $context = null)
    {
        /** @noinspection SpellCheckingInspection */
        $templater = \XF::app()->templater();
        $globalParams = \XF::app()->getGlobalTemplateData();

        $templater->addDefaultParam('xf', $globalParams);

        $onlyInclude = $thread->Forum->field_cache;
        if ($context instanceof Forum) {
            foreach (array_keys($context->field_cache) as $fieldId) {
                if (isset($onlyInclude[$fieldId])) {
                    unset($onlyInclude[$fieldId]);
                }
            }
        }

        $html = $templater->renderMacro(
            'public:qpf_custom_fields_macros',
            'custom_fields_values',
            [
                'type' => 'threads',
                'group' => $group,
                'onlyInclude' => $onlyInclude,
                'set' => $thread->custom_fields,
                'valueClass' => 'message-fields message-fields--' . $group
            ]
        );

        $bbCode = BbCode::renderFromHtml($html);

        return \trim($bbCode);
    }
}
