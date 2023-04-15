<?php

namespace App\Helpers;

use ErrorException;
use App\Models\User;

use RedBeanPHP\Facade as R;


/**
 * This class provides static methods for rendering templates.
 */
class Validator {


    /**
     * Accepts data and a ruleset for validating and sanitizing a given data model
     *
     * @param      array        $data   The data
     * @param      array        $rules  The rules
     * @example [
     *              [ 'key', 'rakit|validation(s)', sanitizer<string|CONSTANT> ],
     *              ...
     *          ]
     *
     * @throws     \ErrorException  Throws error if sanitization string label does not exist
     *
     * @return     array        ( description_of_the_return_value )
     */
    public static function validate(array $data, array $rules) : array {
        $validator = new \Rakit\Validation\Validator();

        $validator->addValidator('unique',          new UniqueRule());
        $validator->addValidator('unique_email',    new UniqueEmailRule());
        $validator->addValidator('min_age',         new MinAgeRule());

        $validators = [];
        $sanitizers = [];

        foreach ($rules as $el) {
            [ $key, $rule, $sanitizer ] = $el;

            $validators[$key] = $rule;
            $sanitizers[$key] = $sanitizer;
        }


        // VALIDATE THE INPUTS
        $validation = $validator->make($data, $validators);

        $validation->validate();

        if ($validation->fails()) {
            return [
                'messages' => $validation->errors()->toArray(),
            ];
        }


        // SANITIZE THE INPUTS
        foreach ($sanitizers as $key => $sanitizer) {

            if (is_string($sanitizer)) {
                $sanitizer = trim(strtolower($sanitizer));

                switch($sanitizer) {
                    case 'email':
                        $data[$key]         = self::sanitizeEmail($data[$key]);
                        $data[$key.'_base'] = self::stripEmailSubaddress($data[$key]);
                        break;
                    case 'htmlspecialchars':
                        $data[$key] = htmlspecialchars($data[$key]);
                        break;
                    default:
                        throw new \ErrorException("Missing \$sanitizer method for '$sanitizer'.");
                        break;
                }

                continue;
            }


            $data[$key] = filter_var($data[$key], $sanitizer);
        }

        return $data;
    }



    // ----------------------------------------
    //  PRIVATE SANITIZATION METHODS
    // ----------------------------------------


    /**
     * Sanitizes email address and returns the provided email string and the base email string for future comparisons
     *
     * @link https://www.lifewire.com/elements-of-email-address-1166413
     * @param      array    $data   The data
     * @param      string   $key    The key
     */
    public static function sanitizeEmail(string $email) : string {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // ASCII filtering based on https://stackoverflow.com/a/22786548/3708807
        $email = iconv('UTF-8', 'ASCII//TRANSLIT', $email);

        return strtolower($email);
    }


    public static function stripEmailSubaddress(string $email) : string {
        // remove +subaddress and return raw base email string
        $email = preg_replace('/\+[\s\S]+?\@/', '@', $email);

        return $email;
    }

}



use Rakit\Validation\Rule;

class UniqueRule extends Rule {
    protected $message = ":attribute :value has been used";

    protected $fillableParams = ['table', 'column', 'except'];

    public function __construct() {
    }

    public function check($value) : bool {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table = $this->parameter('table');
        $except = $this->parameter('except');

        if ($except && $except == $value) {
            return true;
        }

        // do query
        $res = R::findOne($table, "{$column} = ?", [ $value ]);

        // true for valid, false for invalid
        return !!!$res;
    }
}

class UniqueEmailRule extends Rule {

    protected $message = ":attribute :value has been used";

    // protected $fillableParams = [ ];

    public function __construct() {
    }

    public function check($value) : bool {
        // do query
        $user = User::findByEmail($value);

        // true for valid, false for invalid
        return !!!$user;
    }
}


class MinAgeRule extends Rule {
    protected $message = ":attribute :value must be before :date";

    protected $fillableParams = [ 'min_age' ];

    public function __construct() {
    }

    public function check($value) : bool {
        // make sure required parameters exists
        $this->requireParameters(['min_age']);

        // getting parameters
        $minAge = $this->parameter('min_age');

        $beforeDate = '-' . $minAge . ' years';
        $before = (new \DateTime($beforeDate))->format('Y-m-d H:i:s');

        return $value < $before;
    }
}
