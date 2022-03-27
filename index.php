<?php
$user = 'root';
$password = '';

$database = 'html_page';

$servername = 'localhost:3306';
$mysqli = new mysqli(
	$servername,
	$user,
	$password,
	$database
);

if ($mysqli->connect_error) {
	die('Connect Error (' .
		$mysqli->connect_errno . ') ' .
		$mysqli->connect_error);
}

$sql_employees = "SELECT employees.EmployeeID, persons.FirstName, persons.LastName, roles.RoleName, employees.Salary
FROM employees INNER JOIN persons ON employees.Person=persons.PersonID
INNER JOIN roles ON employees.Role=roles.RoleID";

$sql_persons = "SELECT * FROM persons";
$sql_roles = "SELECT * FROM roles";
$result_employees = $mysqli->query($sql_employees);
$result_persons = $mysqli->query($sql_persons);
$result_show_persons = $mysqli->query($sql_persons); //for the persons drop-down list (form)
$result_roles = $mysqli->query($sql_roles);
$result_show_roles = $mysqli->query($sql_roles); //for the roles drop-down list (form)


if (isset($_POST["submit-persons"])) {

	$firstName = $_POST['first-name'];
	$lastName = $_POST['last-name'];
	$DOB = $_POST['DOB'];

	$sql_persons = "INSERT INTO persons (FirstName, LastName, DateOfBirth) VALUES ('$firstName', '$lastName', '$DOB')";
	$mysqli->query($sql_persons);
	echo 'Record inserted successfully';
}

if (isset($_POST["submit-roles"])) {
	$roleName = $_POST['role-name'];

	$sql_roles = "INSERT INTO roles (RoleName) VALUES ('$roleName')";
	$mysqli->query($sql_roles);
	echo 'Record inserted successfully';
}

if (isset($_POST["submit-employees"])) {
	$person = $_POST['person'];
	$role = $_POST['role'];
	$salary = $_POST['salary'];

	$sql_employees = "INSERT INTO employees (Person, Role, Salary) VALUES ('$person', '$role', '$salary')";
	$mysqli->query($sql_employees);
	echo 'Record inserted successfully';
}

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
	<link href="style.css" rel="stylesheet">
	<title>HR Database</title>
</head>

<body>
	<div class="container">
		<h1>HR Database</h1>
		<section>
			<h3 class="text-info">List of Persons</h3>
			<table>
				<tr>
					<th>Person ID</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Date of Birth</th>
				</tr>

				<?php
				while ($rows = $result_persons->fetch_assoc()) {
				?>

					<tr>
						<td><?php echo $rows['PersonID']; ?></td>
						<td><?php echo $rows['FirstName']; ?></td>
						<td><?php echo $rows['LastName']; ?></td>
						<td><?php echo $rows['DateOfBirth']; ?></td>
					</tr>

				<?php
				}
				?>
			</table>

			<form method="post" action="index.php" id="persons-form">
				<fieldset>
					<legend>Add a new person</legend>

					<p>
						<label for="first-name">First name:</label>
						<input type="text" id="first-name" name="first-name">
					</p>

					<p>
						<label for="last-name">Last name:</label>
						<input type="text" id="last-name" name="last-name">
					</p>

					<p>
						<label for="DOB">Date of birth:</label>
						<input type="date" id="DOB" name="DOB">
					</p>

					<input type="submit" value="Submit" name="submit-persons">
				</fieldset>
			</form>

		</section>

		<section>

			<h3>List of Roles</h3>
			<table>
				<tr>
					<th>Role ID</th>
					<th>Role Name</th>
				</tr>

				<?php
				while ($rows = $result_roles->fetch_assoc()) {
				?>

					<tr>
						<td><?php echo $rows['RoleID']; ?></td>
						<td><?php echo $rows['RoleName']; ?></td>
					</tr>

				<?php
				}
				?>
			</table>

			<form method="post" action="index.php" id="roles-form">
				<fieldset>
					<legend>Add a new role</legend>

					<p>
						<label for="role-name">Role name:</label>
						<input type="text" id="role-name" name="role-name">
					</p>

					<input type="submit" value="Submit" name="submit-roles">
				</fieldset>
			</form>

		</section>

		<section>

			<h3>List of Employees</h3>
			<table>

				<tr>
					<th>Employee ID</th>
					<th>Person</th>
					<th>Role</th>
					<th>Salary</th>
				</tr>

				<?php
				while ($rows = $result_employees->fetch_assoc()) {
				?>

					<tr>
						<td><?php echo $rows['EmployeeID']; ?></td>
						<td><?php echo $rows['FirstName'] . " " . $rows['LastName']; ?></td>
						<td><?php echo $rows['RoleName']; ?></td>
						<td><?php echo $rows['Salary']; ?></td>
					</tr>

				<?php
				}
				?>
			</table>

			<form method="post" action="index.php" id="employees-form">
				<fieldset>
					<legend>Add a new employee</legend>

					</p>
					<label for="person">Person:</label>
					<select name="person" id="person" form="employees-form">
						<?php
						while ($rows = $result_show_persons->fetch_assoc()) {
						?>
							<option value="<?php echo $rows['PersonID']; ?>">
								<?php echo $rows['FirstName'] . " " . $rows['LastName']; ?>
							</option>
						<?php
						}
						?>
					</select>
					</p>

					</p>
					<label for="role">Role:</label>
					<select name="role" id="role" form="employees-form">
						<?php
						while ($rows = $result_show_roles->fetch_assoc()) {
						?>
							<option value="<?php echo $rows['RoleID']; ?>">
								<?php echo $rows['RoleName']; ?>
							</option>
						<?php
						}
						?>
					</select>
					</p>

					<p>
						<label for="salary">Salary:</label>
						<input type="number" id="salary" name="salary">
					</p>

					<input type="submit" value="Submit" name="submit-employees">

				</fieldset>
			</form>

		</section>
	</div>
</body>

</html>