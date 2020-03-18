import React, { Component } from 'react';
import { Card, CardHeader, CardBody } from 'reactstrap';

export default class AfterPleno extends Component {
  render() {
    return (
      <div>
        <Card>
          <CardHeader style={{textAlign:'center'}}> Generate Pleno online</CardHeader>
          <CardBody>Hasil Pleno</CardBody>
        </Card>
      </div>
    );
  }
}
