<div class="calendar container border">
    <div class="table">
        <div class="row">
            <div class="col">
                <h1>Today's date: <?php echo $dateToday ?></h1>
            </div>
            <div class="col">
                <input type="date" name="selectedDate" id="calendar" onchange="changeDate()" value="<?= $_SESSION['selectedDate'] ?>">
            </div>
        </div>
        <table class="container-fluid table">
            <thead>
                <tr class="text-center">
                    <td class="col-2"></td>
                    <td>
                        <div class=""><?php echo date("l", strtotime($_SESSION['selectedDate'])); ?></div>
                        <div class=""><?php echo date("d", strtotime($_SESSION['selectedDate'])); ?></div>
                    </td>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $response = file_get_contents('http://localhost:5000/getAppointment?date=' . $getDate);
                $response = json_decode($response, true);

                for ($i = strtotime("09:00"); $i <= strtotime("18:00"); $i += 1800) {
                    $slotTime = date("H:i", $i);

                    $isBooked = false;
                    $isDone = false;
                    $isExpired = strtotime($getDate) < strtotime($dateToday);
                    $isRescheduled = false;
                    $isPending = false;

                    foreach ($response as $result) {
                        if ($result['appointment_start'] === $slotTime && $result['date'] === $getDate) {
                            if ($result['status'] == 3) {
                                $aid = $result['aid'];
                                $user_id = $result['user_id'];
                                $isDone = true;
                            } elseif ($result['status'] == 2) {
                                $aid = $result['aid'];
                                $user_id = $result['user_id'];
                                $isBooked = true;
                            } elseif ($result['status'] == 1) {
                                $aid = $result['aid'];
                                $user_id = $result['user_id'];
                                $isPending = true;
                            } elseif ($result['status'] == 6) {
                                $aid = $result['aid'];
                                $user_id = $result['user_id'];
                                $isRescheduled = true;
                            } else {
                                break;
                            }
                        }
                    }
                ?>
                    <tr>
                        <td class="col-2"><?php echo date("h:i A", strtotime($slotTime)); ?></td>
                        <td>
                            <?php
                            if ($_SESSION['role'] == "user") {
                            ?>
                                <form action="appointment_form.php" method="get">
                                    <input type="hidden" name="date" value="<?php echo $getDate; ?>">
                                    <input type="hidden" name="time" value="<?php echo $slotTime; ?>">
                                    <?php
                                    if ($isBooked) {
                                    ?>
                                        <input type="submit" class="btn btn-light w-100" value="Booked" disabled />
                                    <?php
                                    } elseif ($isDone) {
                                    ?>
                                        <input type="submit" class="btn btn-success w-100" value="Appointment Done" disabled />
                                    <?php
                                    } elseif ($isExpired) {
                                    ?>
                                        <input type="submit" class="btn btn-danger w-100" value="Expired" disabled />
                                    <?php
                                    } else {
                                        if (isset($_GET['user_id']) && isset($_GET['aid'])) {
                                            $user_id = $_GET['user_id'];
                                            $aid = $_GET['aid'];
                                        }
                                    ?>
                                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                        <input type="hidden" name="aid" value="<?= isset($aid) ? $aid : null ?>">
                                        <input type="submit" class="btn btn-primary w-100" value="Available" />
                                    <?php
                                    }
                                    ?>
                                </form>
                                <?php
                            } elseif ($_SESSION['role'] == "admin") {
                                if ($isBooked) {
                                ?>
                                    <a class="btn btn-primary w-100" href="<?php echo './customer_details.php?aid=' . urlencode($aid); ?>"> Booked </a>
                                <?php
                                } elseif ($isPending) {
                                ?>
                                    <button type="button" class="btn btn-primary w-100 " data-bs-toggle="modal" data-bs-target="#approval">
                                        Waiting for approval
                                    </button>
                                    <?php include("approval.php") ?>
                                <?php
                                } elseif ($isDone) {
                                ?>
                                    <button type="button" class="btn btn-success w-100 " data-bs-toggle="modal" data-bs-target="#reschedule" <?= isset($_GET['user_id']) ? "disabled" : "" ?>>
                                        Appointment Done ( Reschedule Available )
                                    </button>
                                    <?php
                                    include("reschedule.php");
                                    $isRescheduled = true;
                                    ?>
                                <?php
                                } elseif ($isRescheduled) {
                                ?>
                                    <button type="button" class="btn btn-success w-100 " data-bs-toggle="modal" data-bs-target="#reschedule" disabled>
                                        Rescheduled
                                    </button>
                                <?php
                                } elseif ($isExpired) {
                                ?>
                                    <a class="btn btn-danger w-100 disabled" href="#"> Expired </a>
                                <?php
                                } else {
                                ?>
                                    <form action="appointment_form.php" method="get">
                                        <input type="hidden" name="date" value="<?php echo $getDate; ?>">
                                        <input type="hidden" name="time" value="<?php echo $slotTime; ?>">

                                        <?php
                                        if ($reschedule) {
                                            $aid = $_GET['aid'];
                                            $user_id = $_GET['user_id'];
                                        ?>
                                            <button class="btn btn-light w-100" type="submit" name="reschule"> Vacant </button>
                                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                            <input type="hidden" name="aid" value="<?= $aid ?>">
                                        <?php } else { ?>
                                            <a class="btn btn-light w-100 disabled" href="#"> Vacant </a>
                                        <?php } ?>
                                    </form>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>