import React, { Component } from 'react';

export default class InputData_AssignApplicant extends Component {
  render() {
    const { label } = this.props;
    const { isChecked } = this.state;
    return (
      <div className="checkbox">
        {' '}
        <label>
          <input
            type="checkbox"
            value={label}
            checked={isChecked}
            onChange={this.toggleCheckboxChange}
          />

          {label}
        </label>
      </div>
    );
  }
}
