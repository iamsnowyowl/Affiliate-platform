import React, { Component } from "react";
import { Popconfirm } from "antd";
import Axios from "axios";

import { multiLanguage } from "../Language/getBahasa";
import { Digest } from "../../containers/Helpers/digest";
import { baseUrl } from "../config/config";

type Props = {
  id_delete: any,
  path: any,
  get: any
};

class ButtonDelete extends Component<Props> {
  deleted = value => {
    const { path } = this.props;
    const auth = Digest(path + "/" + value, "DELETE");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path + "/" + value,
      data: null
    };
    Axios(options).then(res => {
      window.location.reload();
    });
  };

  render() {
    return (
      <Popconfirm
        title={multiLanguage.confirmDelete}
        onConfirm={this.deleted.bind(this, this.props.id_delete)}
        onCancel={this.cancel}
        okText={multiLanguage.yes}
        cancelText={multiLanguage.no}
      >
        <button
          className="btn btn-danger delete-button col-md-auto"
          title={multiLanguage.delete}
        >
          <i className="fa fa-trash"> </i>
        </button>
      </Popconfirm>
    );
  }
}

export default ButtonDelete;
