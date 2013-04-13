<?php

namespace QoopLmao\FileUploaderBundle;

class QoopLmaoFileUploaderEvents
{
    const UPLOAD_INITIALIZE = 'qoop_lmao_file_uploader.upload.initialize';

    const UPLOAD_SUCCESS = 'qoop_lmao_file_uploader.upload.success';

    const UPLOAD_COMPLETED = 'qoop_lmao_file_uploader.upload.completed';

    const UPLOAD_FAILED = 'qoop_lmao_file_uploader.upload.failed';

    const DELETE_INITIALIZE = 'qoop_lmao_file_uploader.delete.initialize';

    const DELETE_SUCCESS = 'qoop_lmao_file_uploader.delete.success';

    const DELETE_COMPLETED = 'qoop_lmao_file_uploader.delete.completed';

    const DOWNLOAD_INITIALIZE = 'qoop_lmao_file_uploader.download.initialize';

    const DOWNLOAD_SUCCESS = 'qoop_lmao_file_uploader.download.success';

    const DOWNLOAD_COMPLETED = 'qoop_lmao_file_uploader.download.completed';

}