foreach ($distributer as $dis_user) {
                            $dis[] = new \MongoDB\BSON\ObjectID($dis_user['_id']);
                        }
                        $retailer = User::whereIn('referralId', $dis)->get();
                        $groups = [];
                        foreach ($retailer as $re_user) {
                            $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                            // $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                            if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                $td = date('j', strtotime($_GET['to']));
                                $groups[$re_user['_id']] = Bets::where('retailerId', new \MongoDB\BSON\ObjectID($re_user['_id']))
                                    ->whereBetween(
                                        'createdAt',
                                        array(
                                            Carbon::createFromDate($fY, $fm, $fd),
                                            Carbon::createFromDate($tY, $tm, $td),
                                        )
                                    )->orderBy('createdAt', 'DESC')->get()->toArray();
                            } elseif ($type == 7 || $type == 6) {
                                $to = date('Y-n-j', strtotime($_GET['to']));
                                $groups[$re_user['_id']] = Bets::where('retailerId', new \MongoDB\BSON\ObjectID($re_user['_id']))->where('DrDate', $to)->orderBy('createdAt', 'DESC')->get()->toArray();
                            }
                        }
                        // echo "<pre>";
                        // print_r($groups);
                        $commission = [];
                        foreach ($groups as $key => $get) {
                            $PlayPoints = 0;
                            $WinPoints = 0;
                            $EndPoint = 0;
                            $RetailerCommission = 0;
                            $DistributerCommission = 0;
                            $SuperDistributerCommission = 0;
                            foreach ($get as $player) {
                                $commission[$key]['userName'] = $super['userName'];
                                $commission[$key]['_id'] = $super['_id'];
                                $commission[$key]['role'] = $super['role'];
                                $PlayPoints += $player['betPoint'];
                                $WinPoints += $player['won'];
                                $EndPoint = $PlayPoints - $WinPoints;
                                $commission[$key]['playPoint'] = $PlayPoints;
                                $commission[$key]['wonPoint'] = $WinPoints;
                                $commission[$key]['endPoint'] = $EndPoint;
                                $RetailerCommission += $player['retailerCommission'];
                                $DistributerCommission += $player['distributerCommission'];
                                $SuperDistributerCommission += $player['superDistributerCommission'];
                                $commission[$key]['TotalRetailerCommission'] = $RetailerCommission;
                                $commission[$key]['TotalDistributerCommission'] = $DistributerCommission;
                                $commission[$key]['TotalSuperDistributerCommission'] = $SuperDistributerCommission;
                            }
                        }
                        echo "<pre>";
                        print_r($commission);
                        die;
                        foreach ($commission as $play) {
                            $totalPlayPoints += $play['playPoint'];
                            $TotalWinPoints += $play['wonPoint'];
                            $EndPoint = $totalPlayPoints - $TotalWinPoints;
                        }
                        $total = [];
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['TotalRetailerCommission'] = $TotalRetailerCommission;
                        $total['TotalDistributerCommission'] = $TotalDistributerCommission;
                        $total['TotalSuperDistributerCommission'] = $TotalSuperDistributerCommission;
                        // echo "<pre>";
                        // print_r($total);die();
