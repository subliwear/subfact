<?php


namespace App\GraphQL\Queries;

use App\Facades\App;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class TaxQuery
{
  public function index($rootValue, array $args, GraphQLContext $context)
  {
    return App::call('TaxController@index', $args);
  }
}
