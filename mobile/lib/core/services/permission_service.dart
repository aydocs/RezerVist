import 'package:permission_handler/permission_handler.dart';
import 'package:shared_preferences/shared_preferences.dart';

class PermissionService {
  static const String _launchCountKey = 'launch_count';

  static Future<void> checkAndRequestLocation() async {
    final prefs = await SharedPreferences.getInstance();
    int launchCount = prefs.getInt(_launchCountKey) ?? 0;
    launchCount++;
    await prefs.setInt(_launchCountKey, launchCount);

    final status = await Permission.locationWhenInUse.status;

    if (status.isGranted) {
      // Permission already granted, do nothing
      return;
    }

    // Every 3 launches logic: 1st, 4th, 7th...
    if (launchCount % 3 == 1) {
      await Permission.locationWhenInUse.request();
    }
  }
}
