
    // API: Get Total Unread Count
    public function apiGetUnreadCount()
    {
        $count = $this->conversationModel->getUnreadCount(auth()->id());
        return $this->response->setJSON(['count' => $count]);
    }
}
