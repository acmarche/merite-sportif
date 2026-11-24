<?php

namespace AcMarche\MeriteSportif\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Rich text field rendered with the Trix editor (https://trix-editor.org).
 * The HTML is stored in a hidden input bound to a <trix-editor> element,
 * see templates/form/trix_widget.html.twig.
 */
class TrixType extends AbstractType
{
    public function getParent(): string
    {
        return TextareaType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'trix';
    }
}
