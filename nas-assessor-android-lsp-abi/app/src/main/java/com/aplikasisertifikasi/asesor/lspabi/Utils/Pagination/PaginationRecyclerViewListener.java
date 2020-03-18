package com.aplikasisertifikasi.asesor.lspabi.Utils.Pagination;

import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;

public abstract class PaginationRecyclerViewListener extends RecyclerView.OnScrollListener {

    LinearLayoutManager linearLayoutManager;
    int totalItemCount = 0;
    int currentOffset = 0;
    int limit;
    int previousTotal = 0;
    int visibleItemCount = 0;
    int pastVisibleItem = 0;
    int lastVisibleItemPosition = 0;
    boolean isLoading = true;

    public PaginationRecyclerViewListener(LinearLayoutManager linearLayoutManager, int limit) {
        this.linearLayoutManager = linearLayoutManager;
        this.limit = limit;
    }

    @Override
    public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
        super.onScrolled(recyclerView, dx, dy);

        visibleItemCount = linearLayoutManager.getChildCount();
        totalItemCount = linearLayoutManager.getItemCount();
        pastVisibleItem = linearLayoutManager.findFirstVisibleItemPosition();
        lastVisibleItemPosition = linearLayoutManager.findLastCompletelyVisibleItemPosition();

        if (isLoading) {
            if (totalItemCount > previousTotal) {
                isLoading = false;
                previousTotal = totalItemCount;
            }
        }

        if (lastVisibleItemPosition != RecyclerView.NO_POSITION &&
                lastVisibleItemPosition == recyclerView.getAdapter().getItemCount() - 1) {
            if (currentOffset + limit >= recyclerView.getAdapter().getItemCount()) {
                currentOffset += limit;
                loadMoreItems(currentOffset);
                isLoading = true;
            }
        }
    }

    protected abstract void loadMoreItems(int currentOffset);
}
