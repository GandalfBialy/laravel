<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;
use App\Contracts\CounterContract;

class Counter implements CounterContract
{
  private $timeout;
  private $cache;
  private $session;
  private $supportsTags;

  // public function __construct(Cache $cache, Session $session, int $timeout)
  public function __construct(Cache $cache, Session $session, $timeout)
  {
    $this->cache = $cache;
    $this->timeout = $timeout;
    $this->session = $session;
    $this->supportsTags = method_exists($cache, 'tags');
  }

  public function increment(string $key, array $tags = null): int
  {
    $sessionId = $this->session->getId();
    $counterKey = "{$key}-counter";
    $usersKey = "{$key}-users";

    $cache = $this->supportsTags && null !== $tags ? $this->cache->tags($tags) : $this->cache;
    // $cache = $this->cache;

    $users = $cache->get($usersKey, []);
    $usersUpdate = [];
    $diffrence = 0;
    $now = now();

    foreach ($users as $session => $lastVisit) {
      if ($now->diffInMinutes($lastVisit) >= $this->timeout) {
        $diffrence--;
      } else {
        $usersUpdate[$session] = $lastVisit;
      }
    }

    if (
      !array_key_exists($sessionId, $users)
      || $now->diffInMinutes($users[$sessionId]) >= $this->timeout
    ) {
      $diffrence++;
    }

    $usersUpdate[$sessionId] = $now;
    $cache->forever($usersKey, $usersUpdate);

    if (!$cache->has($counterKey)) {
      $cache->forever($counterKey, 1);
    } else {
      $cache->increment($counterKey, $diffrence);
    }

    $counter = $cache->get($counterKey);

    return $counter;
  }
}





// namespace App\Services;

// // use Illuminate\Support\Facades\Cache;

// use App\Contracts\CounterContract;
// use Illuminate\Contracts\Cache\Factory as Cache;
// use Illuminate\Contracts\Session\Session;

// class Counter implements CounterContract
// {
//   private $cache;
//   private $session;
//   private $timeout;
//   private $supportsTags;

//   public function __construct(Cache $cache, Session $session, int $timeout)
//   {
//     $this->cache = $cache;
//     $this->session = $session;
//     $this->timeout = $timeout;
//     $this->supportsTags = method_exists($cache, 'tags');
//   }

//   public function increment(string $key, array $tags = null): int
//   {
//     // $sessionId = session()->getId();
//     $sessionId = $this->session->getId();
//     $counterKey = "{$key}-counter";
//     $usersKey = "{$key}-users";

//     $cache = $this->supportsTags && null !== $tags ? $this->cache->tags($tags) : $this->cache;

//     $users = Cache::get($usersKey, []);
//     $usersUpdate = [];
//     $diffrence = 0;
//     $now = now();

//     foreach ($users as $session => $lastVisit) {
//       if ($now->diffInMinutes($lastVisit) >= $this->timeout) {
//         $diffrence--;
//       } else {
//         $usersUpdate[$session] = $lastVisit;
//       }
//     }

//     if (
//       !array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= $this->timeout
//     ) {
//       $diffrence++;
//     }

//     $usersUpdate[$sessionId] = $now;
//     Cache::forever($usersKey, $usersUpdate);

//     if (!Cache::has($counterKey)) {
//       Cache::forever($counterKey, 1);
//     } else {
//       Cache::increment($counterKey, $diffrence);
//     }

//     $counter = Cache::get($counterKey);

//     return $counter;
//   }
// }
