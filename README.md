<h3 style="text-align:center">API for Gym application</h3>
<p>
    Authentication and authorization are implemented with JWT tokens.
    For authorization pass JWT token with like "Bearer {token}" .
</p>
<p>
    Times are formatted as HH:ii (e.g. 18:00, 09:00, 02:00).
    Time that differs from that format is considered invalid.
</p>
<p>
    Routes:
</p>
<table>
<tr>
<th>Route</th>
<th>Method</th>
<th>Body</th>
<th>Description</th>
</tr>
<tr>
    <td>/api/clients/login</td>
    <td>POST</td>
    <td>{email, password}</td>
    <td>Login route for clients. Returns JWT token</td>
</tr>
<tr>
    <td>/api/trainers/login</td>
    <td>POST</td>
    <td>{email, passowrd}</td>
    <td>Login route for trainers. Returns JWT token</td>
</tr>
<tr>
    <td>/trainer</td>
    <td>PUT</td>
    <td>{name, shift_start_time, shift_end_time}</td>
    <td>Updates information about current trainer</td>
</tr>
<tr>
    <td>/trainers</td>
    <td>GET</td>
    <td></td>
    <td>Returns list of all trainers</td>
</tr>
<tr>
    <td>/trainers/{trainerId}/appointments</td>
    <td>GET</td>
    <td></td>
    <td>Returns list of all appointments of the trainer</td>
</tr>
<tr>
    <td>/trainers/{trainerId}/appointments</td>
    <td>POST</td>
    <td>{date, start_time, end_time, companions: array}</td>
    <td>Books an appointment if all listed users and trainer are available</td>
</tr>
<tr>
    <td>/trainers/{trainerId}/schedules</td>
    <td>GET</td>
    <td></td>
    <td>Returns list of free time of trainer</td>
</tr>
<tr>
    <td>/clients/{clientId}/appointments</td>
    <td>GET</td>
    <td></td>
    <td>Returns list of all appointments of the client</td>
</tr>
<tr>
    <td>/client/appointments</td>
    <td>GET</td>
    <td></td>
    <td>Returns list of all appointments of the current client</td>
</tr>
<tr>
    <td>/client/appointments/{appointmentId}</td>
    <td>DELETE</td>
    <td></td>
    <td>Deletes information about appointment</td>
</tr>
</table>
