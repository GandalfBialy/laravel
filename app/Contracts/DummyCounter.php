<?php

namespace App\Services;

use App\Contracts\CounterContract;

class DummyCounter implements CounterContract
{
  public function increment(string $key, array $tags = null): int
  {
    dd('DUMMY COUNTER :D');

    return 0;
  }
}
