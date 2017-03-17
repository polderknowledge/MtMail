<?php
namespace MtMail\Attachment;

interface AttachmentInterface
{
    public function getContent();

    public function getType();

    public function getFilename();
}