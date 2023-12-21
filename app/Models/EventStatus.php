<?php

namespace App\Models;

enum EventStatus: string
{
    case Upcoming = 'UPCOMING';
    case Ongoing = 'ONGOING';
    case Finished = 'FINISHED';
    case Canceled = 'CANCELED';
    case Banned = 'BANNED';
    case Deleted = 'DELETED';
}