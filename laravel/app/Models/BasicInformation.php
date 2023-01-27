<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class BasicInformation
 * @package App\Models
 */
class BasicInformation extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'basic_information';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'genres' => 'array',
        'styles' => 'array',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'master_id',
        'master_url',
        'resource_url',
        'thumb',
        'cover_image',
        'title',
        'year',
        'formats',
        'labels',
        'artists',
        'genres',
        'styles'
    ];

    /**
     * @return EmbedsMany
     */
    public function formats(): EmbedsMany
    {
        return $this->embedsMany(Format::class);
    }

    /**
     * @return EmbedsMany
     */
    public function labels(): EmbedsMany
    {
        return $this->embedsMany(Label::class);
    }

    /**
     * @return EmbedsMany
     */
    public function artists(): EmbedsMany
    {
        return $this->embedsMany(Artist::class);
    }
}
