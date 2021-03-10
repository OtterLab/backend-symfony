<?php 
require_once('../vendor/autoload.php');
require_once('./db.php');
require_once('./se.php');

$RoyalShorelineHotelDB = new RoyalShoreline;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;

$request = Request::createFromGlobals();
$response = new Response();
$session = new Session(new NativeSessionStorage(), new AttributeBag());

$response->headers->set('Content-Type', 'application/json');
$response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Origin', $_ENV['ORIGIN']);
$response->headers->set('Access-Control-Allow-Credentials', 'true');

// Start session
$session->start();

if(!$session->has('sessionOBJ')) {
    $session->set('sessionOBJ', new RoyalShorelineSession);
}

if(empty($request->query->all())) {
    $response->setStatusCode(400);
} elseif($request->cookies->has('PHPSESSID')) {
    if($session->get('sessionOBJ')->is_rate_limited()) {
        $response->setStatusCode(429);
    }
    // Register Account
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'register') {
            if($request->request->has('username') and
                $request->request->has('password') and
                $request->request->has('firstname') and
                $request->request->has('surname') and
                $request->request->has('phone') and
                $request->request->has('email')) {
                $res = $session->get('sessionOBJ')->register(
                    $request->request->getAlnum('username'),
                    $request->request->get('password'),
                    $request->request->get('firstname'),
                    $request->request->get('surname'),
                    $request->request->get('phone'),
                    $request->request->get('email')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
            // Login
        } elseif($request->query->getAlnum('action') == 'login') {
            if($request->request->has('username') and
                $request->request->has('password')) {
                $res = $session->get('sessionOBJ')->login($request->request->getInt('username'),
                    $request->request->get('password'));
                if ($res === false) {
                    $response->setStatusCode(401);
                } elseif(count($res) == 1) {
                    $response->setStatusCode(203);
                    $response->setContent(json_encode($res));
                } elseif(count($res) > 1) {
                    $response->setStatusCode(200);
                    $response->setContent(json_encode($res));
                }
            } else {
                $response->setStatusCode(400);
            }
        } else {
            $response->setStatusCode(400);
        }
    }

       // Check if user already exists
    if($request->getMethod() == 'GET') {
        if($request->query->getAlnum('action') == 'registerexists') {
            if($request->query->has('username')) {
                $res = $RoyalShorelineHotelDB->registerExists($request->query->getInt('username'));
                if($res) {
                    $response->setStatusCode(400);
                } else {
                    $response->setStatusCode(204);
                }
            }

          // Check user is Logged In
        } elseif($request->query->getAlnum('action') == 'isLoggedIn') {
            $res = $session->get('sessionOBJ')->isLoggedIn();
            if($res == false) {
                $response->setStatusCode(403);
            } elseif(count($res) == 1) {
                $response->setStatusCode(200);
                $response->setContent(json_encode($res));
            }

            // Logout 
        } elseif($request->query->getAlnum('action') == 'logout') {
            $session->get('sessionOBJ')->logout();
            $response->setStatusCode(200);
        } else {
            $response->setStatusCode(400);
        }
    }

    // delete Register account
    if($request->getMethod() == 'DELETE') {
        if($request->getMethod() == 'deleteAccount') {
            if($request->request->has('registerid')) {
                $request->request->getDigits('registerid');
                if($res === true) {
                    $response->setStatusCode(200);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                }
            } else {
                $response->setStatusCode(400);
            }
        }
    }

    // Update register details
    if($request->getMethod() == 'PUT') {
        if($request->getMethod() == 'updateAccount') {
            if($request->request->has('firstname') and
            $request->request->has('surname') and
            $request->request->has('phone') and
            $request->request->has('email')) {
                $res = $session->get('sessionOBJ')->updateAccount(
                    $request->request->getAlnum('firstname'),
                    $request->request->get('surname'),
                    $request->request->get('phone'),
                    $request->request->get('email')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
        }
    } // Add Room
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'addRoom') {
            if($request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('roomprice') and
                $request->request->has('roomdescription')) {
                $res = $session->get('sessionOBJ')->addRoom(
                    $request->request->getAlnum('roomimage'),
                    $request->request->get('roomtype'),
                    $request->request->get('roomprice'),
                    $request->request->get('roomdescription')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
        } 
    }  
    // Update Room
    if($request->getMethod() == 'PUT') {
        if($request->query->getAlnum('action') == 'updateRoom') {
            if($request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('roomprice') and
                $request->request->has('roomdescription')) {
                $res = $session->get('sessionOBJ')->updateRoom(
                    $request->request->getAlnum('roomimage'),
                    $request->request->get('roomtype'),
                    $request->request->get('roomprice'),
                    $request->request->get('roomdescription')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
        } 
    } 
    // Delete Room
    if($request->getMethod() == 'DELETE') {
        if($request->getMethod() == 'deleteAccount') {
            if($request->request->has('roomid')) {
                $request->request->getDigits('roomid');
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                }
            } else {
                $response->setStatusCode(400);
            }
        }
    } 
    // Make Booking
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'makeBooking') {
            if($request->request->has('registerid') and
                $request->request->has('roomid') and
                $request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('bookingdate') and
                $request->request->has('numberofadult') and
                $request->request->has('numberofchildren') and
                $request->request->has('checkindate') and
                $request->request->has('checkoutdate')) {
                $res = $session->get('sessionOBJ')->makeBooking(
                    $request->request->getAlnum('roomid'),
                    $request->request->get('roomimage'),
                    $request->request->get('roomtype'),
                    $request->request->get('bookingdate'),
                    $request->request->get('numberofadult'),
                    $request->request->get('numberofchildren'),
                    $request->request->get('checkindate'),
                    $request->request->get('checkoutdate')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
        } 
    }    // Update Booking
    if($request->getMethod() == 'PUT') {
        if($request->query->getAlnum('action') == 'updateBooking') {
            if($request->request->has('registerid') and
                $request->request->has('roomid') and
                $request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('bookingdate') and
                $request->request->has('numberofadult') and
                $request->request->has('numberofchildren') and
                $request->request->has('checkindate') and
                $request->request->has('checkoutdate')) {
                $res = $session->get('sessionOBJ')->updateBooking(
                    $request->request->getAlnum('roomid'),
                    $request->request->get('roomimage'),
                    $request->request->get('roomtype'),
                    $request->request->get('bookingdate'),
                    $request->request->get('numberofadult'),
                    $request->request->get('numberofchildren'),
                    $request->request->get('checkindate'),
                    $request->request->get('checkoutdate')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
        } 
    }   // Delete Booking
    if($request->getMethod() == 'DELETE') {
        if($request->getMethod() == 'deleteBooking') {
            if($request->request->has('bookingid')) {
                $request->request->getDigits('bookingid');
                if($res === true) {
                    $response->setStatusCode(201);
                    $response->setContent(json_encode($res));
                } elseif($res === false) {
                    $response->setStatusCode(403);
                }
            } else {
                $response->setStatusCode(400);
            }
        }
    } 
} else {
    $redirect = new RedirectResponse($_SERVER['REQUEST_URI']);
}

// Print message
$response->send();

?>