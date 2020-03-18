import React, {Component} from 'react';

export default function Table(props){
  return (
    <table>
      <thead>
        <tr>
          <th>username</th>
          <th>first_name</th>
          <th>last_name</th>
          <th>email</th>
          <th>role_name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {
          props.data.map(row => (
            <tr>
              <td>{row.username}</td>
            </tr>
          ))
        }
      </tbody>
    </table>
  )
}
