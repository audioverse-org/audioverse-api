<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Recording;

class RecordingTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'audio',
        'video',
        'm3u8web',
        'm3u8ios',
        'file',
        'sponsor',
        'series',
        'conference',
        'presenters',
        'topics'
    ];

    public function transform(Recording $recording) {

        $transformed = [
            'id' => (int) $recording->recordingId,
            'title' => $recording->title,
            'description' => $recording->description,
            'recording_date' => $recording->recordingDate,
            'publish_date' => $recording->publishDate,
            'duration' => $recording->duration,
            'topics' => $recording->topicNames
        ];

        if ( $recording->siteImageURL != '' ) {
            $transformed['site_image'] = [
                'file' => $recording->siteImageURL,
                'url' => config('avorg.static_url') .'/'. config("avorg.lang_hash.".config('avorg.default_lang')) . '/gallery/sites/_/',
            ];
        }
        return $transformed;
    }

    public function includeAudio(Recording $recording) {
        return $this->collection($recording->audio, new MediaFileTransformer);
    }

    public function includeM3u8web(Recording $recording) {
        return $this->collection($recording->m3u8web, new MediaFileTransformer);
    }
    public function includeM3u8ios(Recording $recording) {
        return $this->collection($recording->m3u8ios, new MediaFileTransformer);
    }
    public function includeVideo(Recording $recording) {
        return $this->collection($recording->video, new MediaFileTransformer);
    }

    public function includeFile(Recording $recording) {
        return $this->collection($recording->file, new MediaFileTransformer);
    }

    public function includeSponsor(Recording $recording) {

        $sponsor = $recording->sponsor;
        if ( $sponsor ) {
            return $this->item($sponsor, new SponsorTransformer);
        }
    }

    public function includeSeries(Recording $recording) {

        $series = $recording->series;
        if ( $series ) {
            return $this->item($series, new SeriesTransformer);
        }
    }

    public function includeConference(Recording $recording) {

        $conference = $recording->conference;

        if ( $conference ) {
            return $this->item($conference, new ConferenceTransformer);
        }
    }
    public function includePresenters(Recording $recording) {

        $presenters = $recording->presenters;
        if ( $presenters ) {
            return $this->collection($presenters, new PresenterTransformer);
        }
    }

    public function includeTopics(Recording $recording) {

        $topics = $recording->topics;
        return $this->collection($topics, new TopicTransformer);
    }

}
